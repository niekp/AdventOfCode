intcode = list(map(int, input("intcode:").split(",")))

for i in range(0, len(intcode) - (len(intcode) % 4), 4):
    if intcode[i] == 1:
        intcode[intcode[i+3]] = intcode[intcode[i+1]] + intcode[intcode[i+2]]
    elif intcode[i] == 2:
        intcode[intcode[i+3]] = intcode[intcode[i+1]] * intcode[intcode[i+2]]

print(",".join(list(map(str, intcode))))
