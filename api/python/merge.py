import subprocess
import sys
import json

data = json.loads(sys.stdin.read())
project_id = data["project_id"]

video_path = data["video_path"]
audio_path = data["audio_path"]

output_video = f"output/{project_id}_merged.mp4"

subprocess.call([
    "ffmpeg", "-i", video_path, "-i", audio_path,
    "-c:v", "copy", "-c:a", "aac", output_video
])

print(json.dumps({
    "success": True,
    "video_path": output_video,
    "project_id": project_id
}))
