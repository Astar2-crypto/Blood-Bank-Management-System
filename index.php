<?php

require_once 'header.php';
?>

<div style="text-align: center; padding: 60px 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 40px;">
    <h1 style="color: #d9534f; font-size: 3em; margin-bottom: 15px; margin-top: 0;">Give Blood, Save Lives</h1>
    <p style="font-size: 1.2em; color: #555; max-width: 700px; margin: 0 auto 30px auto; line-height: 1.6;">
        Welcome to Starwess BloodBank Management System. A single donation can save up to three lives. Join our community of everyday heroes today.
    </p>
    
    <div style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
        <a href="register.php" style="background-color: #d9534f; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-size: 1.1em; font-weight: bold; transition: background 0.3s;">
            🩸 Become a Donor(Register)
        </a>
        <a href="login.php" style="background-color: #333; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-size: 1.1em; font-weight: bold; transition: background 0.3s;">
            🔐 Login to Profile
        </a>
        <a href="request_blood.php" style="background-color: #5cb85c; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-size: 1.1em; font-weight: bold; transition: background 0.3s;">
            🏥 Hospital Portal
        </a>
    </div>
</div>

<div style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 20px;">
    
    <div style="flex: 1; min-width: 250px; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-top: 4px solid #d9534f;">
        <h3 style="color: #d9534f; margin-top: 0;">Why Donate?</h3>
        <p style="color: #666; line-height: 1.5;">Blood is the most precious gift that anyone can give to another person. There is no substitute for human blood. Your decision to donate can save a life, or even several if your blood is separated into its components.</p>
    </div>

    <div style="flex: 1; min-width: 250px; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-top: 4px solid #d9534f;">
        <h3 style="color: #d9534f; margin-top: 0;">Who Can Donate?</h3>
        <p style="color: #666; line-height: 1.5;">Most healthy adults can donate blood. You must be between 18 and 65 years old, weigh at least 50kg, and be in generally good health. Our system includes a secure pre-screening eligibility check.</p>
    </div>

    <div style="flex: 1; min-width: 250px; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-top: 4px solid #5cb85c;">
        <h3 style="color: #5cb85c; margin-top: 0;">For Hospitals</h3>
        <p style="color: #666; line-height: 1.5;">Registered medical facilities and clinics can use our dedicated Hospital Portal to request specific blood units and quantities. Our administrative team ensures rapid processing for critical medical needs.</p>
    </div>

</div>

<?php
require_once 'footer.php';
?>
