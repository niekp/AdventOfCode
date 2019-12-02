import math

fuel = 0
f = open("day1-input-a.txt", "r")
for x in f:
    fuel += math.floor(int(x) / 3)-2

print(fuel)