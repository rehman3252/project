<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>login Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
    <div class="container">
        <div class="row mt-5">
            <div class="col-sm-7 mx-auto">
                <div class="card">
                    <div class="card-header text-center">Login Form</div>
                    <div class="card-body">
                        <form action="{{url('logincheck')}}" method="POST">
                            @csrf
                            <label for="">Email</label>
                            <input type="email" name="email" class="form-control my-2">
                            <label for="">Password</label>
                            <input type="password" name="password" class="form-control">
                            <button type="submit" class="btn btn-info mt-2 w-25">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>