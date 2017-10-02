<html>
<head><title>JobiMarklets - Account Activation Page.</title></head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.4.4/css/bulma.css" />
<link rel="stylesheet" href="/assets/base.css">
<body>
    <div class="container">
        <div class="columns" style="margin-top: 10px;">
            <div class="column is-half is-offset-one-quarter">
                <div class="column">
                    <p>{{ $message}}</p><br/>
                    <a href="/" class="button is-primary">Back to Home Page</a>
                </div>
                @if (!$success)
                    <div class="columns" style="margin-top: 20px;" >
                        <div class="column">
                            <p class="is-size-2">Resend Activation Link</p>
                            <form method="post" action="/account/reactivate">
                                <div class="field is-horizontal">
                                    <div class="field-body">
                                        <div class="field">
                                            <div class="control has-icons-left">
                                                <input type="email" class="input" required aria-errormessage="Enter valid email" placeholder="Enter email" name="email"/>
                                                <span class="icon is-small is-left">
                                            <i class="fa fa-envelope"></i>
                                        </span>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <button class="button is-primary">Re-send</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>
</body>
</html>