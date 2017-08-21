<html>
<head>
<title>New Account Confirmation Email</title>
</head>
<body>
    <p>Hello {{$user->name}},</p>

    <p>Welcome to JobiMarklets. You are one step away from full registration.</p>
    <p> Below is an activation link. Click the link to enable and verify your email account. <br/>
        <a href="{{$host}}/account/activate/{{$checksum}}">{{$host}}/account/activate/{{$checksum}}</a></p>
    <p>If you weren't expecting this email, kindly ignore.<br/>
    Regards,
    </p>
    <p>
    Ndy<br/>
    Product Owner
    </p>
</body>
</html>

