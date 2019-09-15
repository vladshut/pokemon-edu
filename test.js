function add(a, b) {
    return a + b;
}
var _Test = /** @class */ (function () {
    function _Test() {
        this.dataProvider = [];
    }
    _Test.prototype.exec = function () {
        return this.dataProvider.map(this.testFunction);
    };
    return _Test;
}());
var _test_ = new _Test();
_test_.dataProvider = [
    { a: 2, b: 3, expected: 5 },
    { a: 14, b: 15, expected: 29 },
    { a: 12, b: 13, expected: 25 },
    { a: 22, b: 13, expected: 35 },
];
_test_.testFunction = function (data) {
    return add(data.a, data.b) === data.expected;
};
var results = _test_.exec();
console.log(JSON.stringify(results));
