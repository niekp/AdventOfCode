import math
fuel = 0

def fuelRequired(mass):
    return math.floor(mass / 3) - 2

def addFuelForFuel(f):
    global fuel
    fuelWeight = fuelRequired(f)
    if fuelWeight > 0:
        fuel += fuelWeight
        addFuelForFuel(fuelWeight)

f = open("day1-input-a.txt", "r")
for x in f:
    fr = fuelRequired(int(x))
    fuel += fr
    addFuelForFuel(fr)

print(fuel)
