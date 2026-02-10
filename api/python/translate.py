import sys
import json
from deep_translator import GoogleTranslator

if __name__ == "__main__":
    telugu_text = sys.argv[1]

    english = GoogleTranslator(
        source="te",
        target="en"
    ).translate(telugu_text)

    print(json.dumps({
        "success": True,
        "english_text": english
    }, ensure_ascii=False))
