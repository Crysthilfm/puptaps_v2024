<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        td {
            vertical-align: middle;
        }
        .checkbox-design {
            font-size: 20rem;
            color: #000000;
            padding-right: 0;
            margin-right: 0;
        }
        span {
            margin-left: 0;
            padding-left: 0;
        }
    </style>
</head>
<body>
    <div class="first-item">
        <p><b>1.</b>Reason/s for leaving PUP (Put a check on the box of your choice/s).</p>
        <table style="width:100%;">
            <tr>
                <td colspan="1" style="width: 30%">
                    <input class="checkbox-design" type="checkbox"name="Graduation" value="Graduation" readonly="true"
                        @if ($reason == "Graduation")
                            checked="checked"
                        @endif
                    >
                    <span>Graduation</span>
                </td>
                <td colspan="1" style="width: 30%">
                    <input class="checkbox-design" type="checkbox" name="Personal" value="Personal" readonly="true"
                        @if ($reason == "Personal")
                            checked="checked"
                        @endif
                    >
                    <span>Personal</span>
                </td>
            </tr>
            <tr>
                <td colspan="1">
                    <input class="checkbox-design" type="checkbox"name="Financial" value="Financial" readonly="true"
                        @if ($reason == "Financial")
                            checked="checked"
                        @endif
                    >
                    <span>Financial</span>
                </td>
                <td colspan="1">
                    <input class="checkbox-design" type="checkbox"name="Family" value="Family" readonly="true"
                        @if ($reason == "Family")
                            checked="checked"
                        @endif
                    >
                    <span>Family</span>
                </td>
            </tr>
            <tr>
                <td colspan="1">
                    <input class="checkbox-design" type="checkbox"name="Work-related" value="Work-related" readonly="true"
                        @if ($reason == "Work-related")
                            checked="checked"
                        @endif
                    >
                    <span>Work-related</span>
                </td>
                <td colspan="1">
                    <input class="checkbox-design" type="checkbox"name="Academic" value="Academic" readonly="true"
                        @if ($reason == "Academic")
                            checked="checked"
                        @endif
                    >
                    <span>Academic</span>
                </td>
            </tr>
            <tr>
                <td colspan="1">
                    <input class="checkbox-design" type="checkbox"name="Others" value="Others" readonly="true"
                        @if ($reason == "Others")
                            checked="checked"
                        @endif
                    >
                    <span>Others</span>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
