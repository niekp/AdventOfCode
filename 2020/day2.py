import re

print("Input: Ctrl-D or Ctrl-Z start.")

contents = []
while True:
    try:
        line = input()
    except EOFError:
        break
    contents.append(line)

class password:
    def __init__(self, input):
        match = re.search(r'(\d*)-(\d*) (.): (\w*)', input, re.IGNORECASE)

        if match is not None:
            self.firstDigit = int(match.group(1))
            self.lastDigit = int(match.group(2))
            self.letter = match.group(3)
            self.password = match.group(4)

    def isValid1(self):
        return self.password.count(self.letter) >= self.firstDigit and self.password.count(self.letter) <= self.lastDigit
    
    def isValid2(self):
        count = 0
        if self.password[self.firstDigit-1] == self.letter:
            count+=1
        if self.password[self.lastDigit-1] == self.letter:
            count+=1
        return count == 1

valid1 = 0
valid2 = 0
for line in contents:
    p = password(line)

    if p.isValid1():
        valid1+=1

    if p.isValid2():
        valid2+=1

print("Valid 1: {0}".format(valid1))
print("Valid 2: {0}".format(valid2))