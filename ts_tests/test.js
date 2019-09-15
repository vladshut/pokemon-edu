
function add(a, b) {
    return a + b;
}

it('test addition with data provider - provider function', function () {
    function plusProvider() {
        return [
            { a: 2, b: 3, expected: 5 },
            { a: 14, b: 15, expected: 29 },
            { a: 12, b: 13, expected: 25 },
            { a: 22, b: 13, expected: 35 },
        ];
    }
    using(plusProvider, function (data) {
        it('should calc with operator +', function () {
            var result = add(data.a, data.b);
            expect(result).toEqual(data.expected);
        });
    });
});
