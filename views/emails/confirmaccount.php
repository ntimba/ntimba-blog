<!DOCTYPE html>
<html>
<head>
    <title>Confirmation d'inscription</title>
</head>
<body>
    <h2>Bonjour <?= $fullName; ?>,</h2>
    <p>Merci pour votre inscription sur notre site. Veuillez confirmer votre compte en cliquant sur le lien ci-dessous:</p>
    <p>
        <a href="<?= $confirmationLink; ?>">
            Confirmez votre compte
        </a>
    </p>
    <p>Si vous n'avez pas créé de compte sur notre site, veuillez ignorer cet e-mail.</p>
    <p>Cordialement,</p>
    <p>Chancy Ntimba</p>
</body>
</html>






