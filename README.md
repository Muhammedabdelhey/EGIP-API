# Alzheimer's Patient Monitoring System - BALZ

We are excited to share the details of our successful graduation project, **BALZ - an Electronic Guide for Alzheimer's Patients**, which earned us an A+ grade! This project was designed to assist Alzheimer's patients by providing a comprehensive solution across three interconnected parts: a mobile app, smart house hardware, and an API. The system is built to enhance patient safety, manage daily tasks, and streamline communication between caregivers and patients.

## Project Overview

### Part 1: Mobile App (Flutter)
The mobile app serves as a key component to address memory loss issues faced by Alzheimer's patients. The app includes two roles: caregiver and patient, with different sets of privileges:
- **Caregiver Role**: Caregivers can manage multiple patients, add memories, schedule tasks (e.g., medication reminders), and receive notifications for urgent events at the patient's location.
- **Patient Role**: Patients can maintain their own memory library, manage daily tasks, and receive reminders for task completion and urgent alerts.

### Part 2: Smart House Hardware
We developed a smart home system powered by **Raspberry Pi** to ensure the patient's safety. This hardware includes:
- **Fire Detection System**: Detects fire and automatically closes gas valves while triggering alarms.
- **Water Leak Detection**: Shuts off the water valve when a leak is detected, preventing flooding.
- **Recognition Technology**: Identifies individuals entering the patient's home and ensures secure access.
- **Alert System**: Sends real-time notifications to both the patient and caregiver via a buzzer and mobile alerts.

### Part 3: Backend API (Laravel)
The core of the system is the **Laravel API** that integrates the smart home system with the mobile app, ensuring secure communication, data management, and real-time notifications.

#### Key Backend Features
- **Authentication & Authorization**: Implemented **JWT (JSON Web Token)** for secure user authentication and **middleware** for role-based authorization.
- **Real-time Notifications**: Utilized **Pusher** to deliver real-time notifications to caregivers and patients for urgent events.
- **Repository & Service Layer Patterns**: Applied these patterns to ensure clean, maintainable, and scalable code structure.
- **Task Scheduling with Cron Jobs**: Managed task statuses, such as medication reminders and safety alerts, through a scheduled **Cron job**.
- **Data Validation**: Custom validation requests ensure data integrity and proper communication between the mobile app and the smart house system.

### Technologies Used
- **Backend**: Laravel, MySQL
- **Frontend**: Flutter (Mobile App)
- **Hardware**: Raspberry Pi, Arduino (for smart house integration)
- **Notifications**: Pusher (Real-time)
- **Authentication**: JWT (JSON Web Token)
- **Data Storage**: MySQL Database

## Conclusion
The Alzheimer's Patient Monitoring System (BALZ) integrates software and hardware to provide a comprehensive, secure, and user-friendly system to assist Alzheimer's patients and their caregivers. By using **Flutter** for mobile, **Laravel** for backend API, and **Raspberry Pi** for hardware integration, we created a seamless and reliable platform for task management, patient safety, and real-time monitoring.
