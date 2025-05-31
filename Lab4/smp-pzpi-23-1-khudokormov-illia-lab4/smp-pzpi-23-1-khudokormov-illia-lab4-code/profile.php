<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Shop - Profile</title>
    <style>
        html, body {
            margin: 0;
            height: 100%;
            display: flex;
            flex-direction: column;
            font-family: Arial, sans-serif;
        }
        
        .profile-container {
            display: flex;
            flex: 1;
            align-items: center;
            justify-content: center;
        }
        
        .profile-form {
            display: flex;
        }
        
        .image-section {
            display: flex;
            flex: 1;
            flex-direction: column;
        }
        
        .profile-image {
            width: 500px;
        }
        
        .file-input {
            display: none;
        }
        
        .upload-button {
            height: 50px;
        }
        
        .info-section {
            display: flex;
            flex: 3;
            flex-direction: column;
        }
        
        .input-row {
            display: flex;
            flex-direction: row;
        }
        
        .profile-input {
            font-size: 24px;
            margin: 50px;
            padding: 5px;
        }
        
        .profile-input::placeholder {
            font-size: 20px;
            font-family: Arial, sans-serif;
            color: black;
        }
        
        .description-textarea {
            font-size: 24px;
            margin: 50px;
            height: 300px;
            margin-right: 50px;
            resize: none;
            padding: 5px;
        }
        
        .error-message {
            font-size: 30px;
            margin: 50px;
            color: red;
        }
        
        .success-message {
            font-size: 30px;
            margin: 50px;
            color: green;
        }
        
        .save-button {
            font-size: 24px;
            margin-left: 900px;
            padding: 10px;
            margin-right: 50px;
        }
        
        .hidden {
            visibility: collapse;
        }
        
        .visible {
            visibility: visible;
        }
    </style>
</head>

<body>
    <div class="profile-container">
        <form method="POST" enctype="multipart/form-data" action="index.php?account" class="profile-form">
            <div class="image-section">
                <img src="<?php 
                    $profilePic = glob('uploads/' . $customer_id . '.*'); 
                    echo empty($profilePic) ? "images/placeholder_icon.png" : $profilePic[0]; 
                ?>" class="profile-image">
                <input id="profilefile" type="file" name="profilefile" class="file-input" accept="image/*" onchange="this.form.submit()" />
                <button onclick="document.getElementById('profilefile').click()" class="upload-button" type="button">
                    Upload
                </button>
                <?php if (isset($uploadSuccess) && $uploadSuccess): ?>
                    <p class="success-message">Profile picture uploaded successfully!</p>
                <?php endif; ?>
                <?php if (isset($uploadError)): ?>
                    <p class="error-message"><?php echo $uploadError; ?></p>
                <?php endif; ?>
            </div>
            <div class="info-section">
                <div class="input-row">
                    <?php $userProfile = fetchCustomerProfile(); ?>
                    <input type="text" 
                           value="<?php echo htmlspecialchars($userProfile['first_name'] ?? ''); ?>" 
                           name="firstName" 
                           placeholder="Name" 
                           class="profile-input">
                    <input type="text" 
                           value="<?php echo htmlspecialchars($userProfile['last_name'] ?? ''); ?>" 
                           name="lastName" 
                           placeholder="Surname" 
                           class="profile-input">
                    <input type="date" 
                           value="<?php echo $userProfile['birth_date'] ?? ''; ?>" 
                           name="birthDate" 
                           class="profile-input">
                </div>
                <textarea name="description" 
                          placeholder="Brief Description" 
                          class="description-textarea"><?php echo htmlspecialchars($userProfile['profile_description'] ?? ''); ?></textarea>
                <p class="error-message <?php echo !isset($changeSuccess) || $changeSuccess ? 'hidden' : 'visible'; ?>">
                    Поля заповнені неправильно
                </p>
                <?php if (isset($changeSuccess) && $changeSuccess): ?>
                    <p class="success-message">Profile updated successfully!</p>
                <?php endif; ?>
                <input type="submit" value="Зберігти" class="save-button">
            </div>
        </form>
    </div>
</body>
</html>