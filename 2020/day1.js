// Paste puzzle input
var input = `

`;

var checkAgainst = 2020;
var data = input.split('\n').map(x => parseInt(x));

function getMatch(input) {
    for (var i in data) {
        if (data[i] + input == checkAgainst) {
            return data[i];
        }
    }

    return null;
}

function getMatchThree(input) {
    for (var i in data) {
        for (var y in data) {
            if (data[i] + data[y] + input == checkAgainst) {
                return data[i] * data[y] * input;
            }
        }
    }

    return null;
}

data.forEach(x => {
    var match = getMatchThree(x);
    if (match) {
        console.log(match);
    }
})

