<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #f6f6f6;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border: 1px rgb(186, 186, 186) solid;
        }
        .header-bar {
            background-color: #74148c;
            height: 8px;
            border-radius: 10px 10px 0 0;
            margin-top: -20px;
            margin-left: -20px;
            margin-right: -20px;
        }
        h1 {
            color: #74148c;
            font-size: 24px;
            text-align: center;
            margin-top: 15px;
        }
        .icon {
            font-size: 50px;
            color: #74148c;
            text-align: center;
            margin-top: 10px;
        }
        .success-message {
            color: #28a745;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
        .details-section {
            margin: 20px 0;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #74148c;
            margin-bottom: 10px;
            border-bottom: 2px solid #74148c;
            padding-bottom: 5px;
        }
        .details p {
            font-size: 16px;
            line-height: 1.6;
            margin: 8px 0;
        }
        .field-label {
            font-weight: bold;
            color: #74148c;
        }
        .footer {
            font-size: 14px;
            color: #666;
            text-align: center;
            margin-top: 30px;
        }
        .rights-reserved {
            font-size: 12px;
            color: #aaa;
            text-align: center;
            margin-top: 10px;
        }
        .footer-bar {
            background-color: #74148c;
            height: 6px;
            border-radius: 0 0 10px 10px;
            margin-top: 20px;
            margin-left: -20px;
            margin-right: -20px;
            margin-bottom: -20px;
        }
        .download-button {
            display: inline-block;
            padding: 10px 20px;
            margin: 20px auto;
            background-color: #74148c;
            color: #ffffff;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            text-align: center;
            transition: background-color 0.3s ease;
        }
        .download-button:hover {
            background-color: #5e1070;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-bar"></div>



        <h1>Booking Confirmation</h1>

        <p class="success-message">Your booking was successful! Here are your booking details:</p>

        <!-- Counsellor and Session Details -->
        <div class="details-section">
            <div class="section-title">Counsellor & Session Information</div>
            <div class="details">
                <p><span class="field-label">Counsellor Name:</span> {{ $counsellor->full_name_with_rate }}</p>
                <p><span class="field-label">Session Time:</span> {{ $timeslot->time }}</p>
                {{--  <p><span class="field-label">Location:</span> {{ $location }}</p>  --}}
            </div>
        </div>

        <!-- Client Details -->
        <div class="details-section">
            <div class="section-title">Client Information</div>
            <div class="details">
                <p><span class="field-label">Name:</span> {{ $formDetails['name'] ?? 'N/A' }}</p>
                <p><span class="field-label">Email:</span> {{ $formDetails['email'] }}</p>
                <p><span class="field-label">Phone:</span> {{ $formDetails['mobile_no'] }}</p>
                <p><span class="field-label">Faculty:</span> {{ $formDetails['faculty'] }}</p>
            </div>
        </div>

        <!-- Download Button -->
        <div class="footer">
            <a  class="download-button">Download Booking Details</a>
        </div>

        <div class="footer">
            <p>If you have any questions, feel free to reach out to us.</p>
        </div>

        <div class="rights-reserved">
            <p>Sitharana Psychological Counseling Center © 2024, Sabaragamuwa University of Sri Lanka. All rights reserved.</p>
        </div>

        <div class="footer-bar"></div>
    </div>
</body>
</html>
