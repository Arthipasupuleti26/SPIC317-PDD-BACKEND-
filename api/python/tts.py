import sys
import json
import asyncio
import edge_tts

data = json.loads(sys.stdin.read())

text = data["text"]
project_id = data["project_id"]
voice = data["voice"]                 # e.g. "en-US-AriaNeural"
style = data.get("style", None)       # e.g. "cheerful"
rate = data.get("rate", "0%")         # speed
pitch = data.get("pitch", "0Hz")

output = f"output/{project_id}.wav"

if style:
    communicate = edge_tts.Communicate(text, voice, style=style, rate=rate, pitch=pitch)
else:
    communicate = edge_tts.Communicate(text, voice, rate=rate, pitch=pitch)

asyncio.run(communicate.save(output))

print(json.dumps({
    "success": True,
    "audio": output,
    "project_id": project_id
}))
