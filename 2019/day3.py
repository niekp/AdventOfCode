class path:
    def __init__(self, wire): 
        self.wire = wire
        self.points = []
        self.__calculate()

    def __calculate(self):
        x = 0
        y = 0
        
        for p in self.wire.split(","):
            direction = p[:1]
            amount = int(p[1:])
            step = 1

            # Invert the amount on left & up
            if direction == "L" or direction == "U":
                step = -1

            # Move in that direction
            for s in range(0, amount):
                if direction == "R" or direction == "L":
                    x += step
                elif direction == "U" or direction == "D":
                    y += step

                # Keep track of points
                self.points.append(point(x, y))

class point:
    def __init__(self, x, y): 
        self.x = x
        self.y = y

def xy(point):
    return "%dx%d" % (point.x, point.y)

def getIntersections(path1, path2):
    intersections = []
    mappedp2 = list(map(xy, path2.points))

    for p in path1.points:
        if xy(p) in mappedp2:
            intersections.append(point(p.x, p.y))
    return intersections

# Read input
wires = open("day3-input-a.txt", "r").readlines();
wire1 = wires[0].replace("\n", "").replace("\r", "")
wire2 = wires[1].replace("\n", "").replace("\r", "")

# Draw paths
p1 = path(wire1)
p2 = path(wire2)

# Find intersections
intersections = getIntersections(p1, p2)

shortestDistance = None
closestIntersection = None
for i in intersections:
    distance = abs(i.x) + abs(i.y)
    if shortestDistance is None or distance < shortestDistance:
        closestIntersection = i
        shortestDistance = distance

if not closestIntersection is None:
    print("Closest intersection is %s with a distance of %d" % (xy(closestIntersection), shortestDistance))

# Part 1: 731 (in just 7 minutes ;p)
