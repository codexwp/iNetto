
### iNetto - ISP Billing of Mikrotik Router
iNetto is a powerful ISP billing software built using Laravel based on
Mikrotik Router OS, designed to simplify and automate the billing processes for internet service providers. With excellent features, 
iNetto offers a streamlined solution to efficiently manage customer accounts and payments. It ensures accurate billing, enhances customer experience, and reduces administrative overhead for ISPs.

- Version: 1.0
- Release Date: 1 January, 2025
- Author: [CodeXwp](http://codexwp.com)

#### Features
- Admin Dashboard: Modern UI built with Sneat Admin Template, offering real-time analytics and insights.
- Seller Management: Add, edit, and manage seller profiles, fund transfer, and payment history.
- User Management: Add, edit, and manage user profiles, bulk CSV export/import, bill pay, and payment history.
- Billing System: Pay bill to single/bulk users. Automated enable/disable user according to expire date and payment reminders.
- RouterOS API Integration: Seamless integration with Mikrotik RouterOS for bandwidth management and client authentication.
- Payment Gateways: Support for Stripe, bKash, and offline payment methods.
- SMS Notifications: Integration with Twilio and BulkSMSBD for sending automated messages.
- Packages: Define and manage various package plans for your users.
- Tariffs: Define and manage various tariffs and package's cost for your sellers.
- Reports: Generate detailed reports on SMS and payments of users and sellers.
- Responsive Design: Fully optimized for desktops, tablets, and mobile devices.


#### Demo URL - [http://inetto.codexwp.com](http://inetto.codexwp.com)
```
Admin Login – admin@inetto.com & 123456

Seller Login – seller1@inetto.com & 123456

User Login – user1@inetto.com & 123456
```
#### Note : Migration & some other files are missing here. So don't try to install.

#### Buy Full Script and License Key From Here - [https://www.codexwp.com/softwares/](https://www.codexwp.com/softwares/)


-------

This document will guide you through the installation and usage of this software.

--------------------------------------------------
1. SYSTEM REQUIREMENTS
--------------------------------------------------
- PHP 8.1 or higher
- Laravel 10
- MySQL 5.7 or higher (or MariaDB equivalent)
- Composer installed on the server
- Node.js and NPM installed
- Web server (Apache, Nginx, etc.)
- Enabled PHP extensions: OpenSSL, PDO, Mbstring, Tokenizer, XML, cURL
- Mikrotik RouterOS 6+ (Enable API service, allow 8087 port in firewall, user credentials)

--------------------------------------------------
2. INSTALLATION GUIDE
--------------------------------------------------
- Upload the contents of the `Upload/` folder to your web server.
- Run `composer install` to install PHP dependencies.
- Run `npm install` to install frontend dependencies.
- Build the frontend assets by running `npm run build`.
- Open your browser, visit (yourdomain.com/install) and follow the instructions.
- For details documentation visit here [http://inetto.codexwp.com/public/docs](http://inetto.codexwp.com/public/docs)


--------------------------------------------------
3. LICENSE
--------------------------------------------------
This software is licensed under the terms specified in the [LICENSE.txt](LICENSE) file. Please review it before use.

--------------------------------------------------
4. CREDITS
--------------------------------------------------
This project uses the following third-party libraries and services:
- [Sneat Admin Template](https://themeselection.com/) (MIT License)
- [Bootstrap](https://getbootstrap.com/) (MIT License)
- [jQuery](https://jquery.com/) (MIT License)
- [Vue.js](https://vuejs.org/) (MIT License)
- [SweetAlert](https://sweetalert.js.org/) (MIT License)
- [Mikrotik RouterOS API](https://github.com/BenMenking/routeros-api) (Check specific license)
- [Stripe API](https://stripe.com/)
- [bKash API](https://developer.bkash.com/)
- [BulkSMSBD](https://www.bulksmsbd.com/)
- [Twilio API](https://www.twilio.com/)


--------------------------------------------------
5. CONTACT
--------------------------------------------------
If you need support or have queries, please contact:
- Email: info@codexwp.com
- Support: [support.codexwp.com](http://support.codexwp.com)

Thank you for using iNetto!
