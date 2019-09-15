function add(a: number, b: number): number {
	return a + b;
}

class _Test {
    public dataProvider: any[] = [];
    public testFunction: (testData: any) => boolean;

    public exec(): boolean[] {
        return this.dataProvider.map(this.testFunction);
    }
}

const _test_ = new _Test();

_test_.dataProvider = [
            { a: 2, b: 3, expected: 5 },
            { a: 14, b: 15, expected: 29 },
            { a: 12, b: 13, expected: 25 },
            { a: 22, b: 13, expected: 35 },
        ];
_test_.testFunction = data => {
    return add(data.a, data.b) === data.expected;
};

const results = _test_.exec();

console.log(JSON.stringify(results));
