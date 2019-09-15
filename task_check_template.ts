//ANSWER//

class _Test {
    public dataProvider: any[] = [];
    public testFunction: (testData: any) => boolean;

    public exec(): boolean[] {
        return this.dataProvider.map(this.testFunction);
    }
}

const _test_ = new _Test();

// *** TEST_SUITE STARTS ***
//TEST_SUITE//
// **** TEST_SUITE ENDS ****

const results = _test_.exec();

console.log('<RESULT>' + JSON.stringify(results) + '</RESULT>');
