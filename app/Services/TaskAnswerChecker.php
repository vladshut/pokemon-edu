<?php


namespace App\Services;


use App\Exceptions\JsNotExecuted;
use App\Exceptions\TsNotCompiled;
use App\Task;
use App\User;
use Illuminate\Support\Facades\File;

final class TaskAnswerChecker
{
    /**
     * @param Task $task
     * @param string $answer
     * @param User $user
     * @return bool[]
     * @throws TsNotCompiled
     * @throws JsNotExecuted
     */
    public function check(Task $task, string $answer, User $user): array
    {
        $fileName = 'task_' . $task->id . '__user_' . $user->id;
        $template = File::get('task_check_template.ts');
        $templateVariables = [
            'ANSWER' => $answer,
            'TEST_SUITE' => $task->successCriteria,
        ];

        foreach ($templateVariables as $key => $item) {
            $templateVariables["//$key//"] = $item;
            unset($templateVariables[$key]);
        }

        $tsFileContent = str_replace(array_keys($templateVariables), array_values($templateVariables), $template);

        $tsFilePath = 'task_checking/' . $fileName . '.ts';
        File::put($tsFilePath, $tsFileContent);

        $result = shell_exec('node_modules/.bin/tsc ' . $tsFilePath);
        File::delete($tsFilePath);

        $isError = strpos($result, ' error ') !== false;

        if ($isError) {
            $errors = str_replace($tsFilePath, 'code.ts', $result);
            throw TsNotCompiled::instance(trim($errors));
        }

        $jsFilePath = substr($tsFilePath, 0, -2) . 'js';

        $result = shell_exec('node_modules/.bin/vm2 ' . $jsFilePath);
        File::delete($jsFilePath);

        $pattern = "/<RESULT>(.*?)<\/RESULT>/";
        preg_match($pattern, $result, $matches);

        $isError = !isset($matches[1]);

        if ($isError) {
            throw JsNotExecuted::instance();
        }

        $result = $matches[1];

        $result = json_decode($result, true);

        return $result;
    }
}
