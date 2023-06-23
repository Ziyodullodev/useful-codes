

f = open("subtitle/matrix.srt", "r+")
all_words = f.read()
f.close()

for i in all_words.split("-->"):
    word = i.split("\n")
    if len(word) == 2:
        continue
    print(word[1], "-->" , word[2])
