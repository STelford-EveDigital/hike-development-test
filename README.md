## Hike Development Test

This project has a 4 hour max timelimit and has the below brief:

> Create a booking system for *__Haydens Garage__*
>
> Capture the following information and store within the database
> - Name
> - Email Address
> - Phone Number
> - Vehicle Make/Model
> - Date & Time Of Booking
> 
> Customers can booking appointments in 30 minute slots. 
> The garage is open 9-17:30, Monday-Friday.
>
> Admin should be able to:
> - Block out date and timeslots to prevent bookings. 
> - View upcoming bookings from admin interface
> - Filter appointments to a specific day.
> 
> On successfully booking, a confirmation email should be sent to admin and customer

## Development Environment

- Project is built with:
    - Laravel 10
    - Laravel Breeze for Authentication
    - Vue 3
    - Tailwind CSS
- Run locally using Laravel Valet with a URL of `http://hike-seo-test.test/`
- Local database run through DBngin, `MySQL v8.0.33 Intel|ARM` accessible through port `3306` with a database name of `hike-development-test`.
- Email is routed through `MailTrap` for local testing - Request access to view mailbox or be added to **Auto Forward** list to receive copy.