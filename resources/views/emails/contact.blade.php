<!DOCTYPE html>
<html>
<head>
<style>
  body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
  .card { background: #fff; border-radius: 10px; padding: 32px; max-width: 500px; margin: 0 auto; }
  h2 { color: #e8192c; margin-bottom: 20px; }
  .field { margin-bottom: 14px; }
  .label { font-size: 12px; color: #999; margin-bottom: 4px; }
  .value { font-size: 15px; color: #111; }
  .message { background: #f9f9f9; padding: 16px; border-radius: 8px; margin-top: 8px; }
  footer { text-align: center; margin-top: 24px; font-size: 12px; color: #aaa; }
</style>
</head>
<body>
<div class="card">
  <h2>📬 New Contact Message</h2>
  <div class="field">
    <div class="label">Name</div>
    <div class="value">{{ $data['name'] }}</div>
  </div>
  <div class="field">
    <div class="label">Email</div>
    <div class="value">{{ $data['email'] }}</div>
  </div>
  <div class="field">
    <div class="label">Subject</div>
    <div class="value">{{ $data['subject'] }}</div>
  </div>
  <div class="field">
    <div class="label">Message</div>
    <div class="message">{{ $data['message'] }}</div>
  </div>
  <footer>Express Minimarket — Contact Form</footer>
</div>
</body>
</html>