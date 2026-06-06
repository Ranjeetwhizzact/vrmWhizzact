# Honest Health Care System - Master Manual

Welcome to the official documentation for the Honest Health Care System. To make it easier for each role to understand their specific tasks, we have separated the instructions into step-by-step guides.

### 📋 Role-Based Manuals

1.  **[Company Admin Manual](admin_manual.md)**
    *   *Target Audience:* System Administrators and Managers.
    *   *Key Topics:* Doctor Setup, Zoom Integration, Patient Management, Scheduling, and Reporting.

2.  **[Doctor Manual](doctor_manual.md)**
    *   *Target Audience:* Medical Examiners and Physicians.
    *   *Key Topics:* Profile Setup, Digital Signatures, Joining Zoom Calls, and Generating Medical Reports.

3.  **[Company User (Staff) Manual](user_manual.md)**
    *   *Target Audience:* Data Entry Staff and Patient Coordinators.
    *   *Key Topics:* Patient Search, Communication Logs, Bulk Uploads, and Data Exporting.

---

### 🛠 General Troubleshooting

#### Meeting Link Not Working
*   **For Doctors:** Ensure your Zoom SDK credentials are correct in your profile settings.
*   **For Patients:** Check if the patient has a stable internet connection. They can join via any modern browser or the Zoom mobile app.

#### Notification Failures
*   If a patient claims they didn't receive an SMS, verify that their phone number is entered with the correct country code and without leading zeros (e.g., use 919876543210).
*   Check the system logs to see if the SMS/Email service returned an error.

#### Exporting Issues
*   If the Excel export is empty, ensure your filters are not too restrictive. Try clearing all filters and exporting again.

---
*Manual Version 1.1 | Honest Health Care System*
