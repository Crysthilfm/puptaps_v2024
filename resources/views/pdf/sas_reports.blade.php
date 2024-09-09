<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        @media print {
            .pagebreak { page-break-before: always; } /* page-break-after works, as well */
        }
        @page{
            margin-top: 130px;
            margin-bottom: 100px;

            margin-left: 70px;
            margin-right: 70px;
        }
        
    </style>
</head>
<body>
    <div style="align-items: center;">

        @if($data)
            {!! $data !!}
        @endif
    </div>
</body>
</html>