{{-- resources/views/emails/case_forwarded.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Case Forwarded</title>
</head>
<body style="Margin:0;padding:0;background-color:#f6f6f6;font-family:Arial,sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
      <td align="center" style="padding:20px 0;">
        <!-- Main container widened to 640px -->
        <table width="640" cellpadding="0" cellspacing="0" border="0" style="background-color:#ffffff;border-radius:8px;overflow:hidden;">
          <!-- Header -->
          <tr>
            <td style="padding:24px 20px 0 20px;font-size:20px;font-weight:bold;color:#333333;">
              Case Forwarded
            </td>
          </tr>

          {{-- Loop through the chain --}}
          @foreach($emailQueryChain as $email)
            <tr>
              <td style="padding:12px 20px;">
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                  <tr>
                    <td align="{{ $email->type === 'outgoing' ? 'left' : 'right' }}">
                      <!-- “Bubble” remains 70% of container -->
                      <table width="70%" cellpadding="0" cellspacing="0" border="0"
                             style="background-color:{{ $email->type === 'outgoing' ? '#e0f7fa' : '#e8eaf6' }};
                                    border-radius:8px;
                                    margin-bottom:8px;
                                    overflow:hidden;">
                        <!-- Meta row -->
                        <tr>
                          <td style="padding:8px 12px;font-size:12px;color:#666666;line-height:1.4;">
                            <div><strong>From:</strong> {{ $email->sender_email }}</div>
                            <div><strong>To:</strong> {{ $email->receiver_email }}</div>
                            <div><strong>Date:</strong> {{ $email->email_received_date ?? $email->created_date }}</div>
                          </td>
                        </tr>
                        <!-- Subject -->
                        <tr>
                          <td style="padding:0 12px 8px 12px;font-size:14px;font-weight:bold;color:#333333;">
                            {{ strip_tags($email->email_subject) }}
                          </td>
                        </tr>
                        <!-- Content -->
                        <tr>
                          <td style="padding:0 12px 12px 12px;font-size:14px;line-height:1.5;color:#333333;">
                            {!! $email->email_content !!}
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          @endforeach

          <!-- Footer padding -->
          <tr>
            <td style="padding:0 20px 24px 20px;"></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <div style="font-family: Arial, sans-serif; color: #333; font-size: 14px; line-height: 1.4;">
        {!! $SENDER_EMAIL_PREVIEW ?? '' !!}
    </div>
</body>
</html>
