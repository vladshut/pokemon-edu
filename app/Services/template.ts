// *** ANSWER STARTS ***
//ANSWER//
// **** ANSWER ENDS ****

class _Test {
    public dataProvider: any[] = [];
    public testFunction: (testData: any) => boolean;

    public exec(): boolean[] {
        return this.dataProvider.map(this.testFunction);
    }
}

const _test_ = new _Test();

// *** ANSWER STARTS ***
//TEST_SUITE//
// **** ANSWER ENDS ****

const results = _test_.exec();

console.log(JSON.stringify(results));
