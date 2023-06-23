import requests

def translate(text, source_lang, target_lang):
    url = f"https://translate.googleapis.com/translate_a/single?client=gtx&sl={source_lang}&tl={target_lang}&dt=t&q={text}"
    response = requests.get(url)
    data = response.json()

    if data and len(data[0]) > 0 and len(data[0][0]) > 0:
        return data[0][0][0]
    else:
        return False

f = open("matrix.srt", "r+")
all_words = f.read()
f.close()

word = "hi, how are you ?"
to_lang = "uz"
in_lang = "en"
translated_word = translate(word, in_lang, to_lang)
print(translated_word)