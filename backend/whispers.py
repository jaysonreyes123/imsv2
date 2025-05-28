import whisper

model = whisper.load_model("tiny")
result = model.transcribe("https://vocalboothtogo.com/wp-content/uploads/2020/05/VO-BOOTH-SAMPLE.mp3",fp16=False)
print(result["text"])
