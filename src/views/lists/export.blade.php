<!DOCTYPE html>
<html lang="pt">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
    <table>
        <thead>
            <tr>
                @foreach($columns as $column)
                <th>
                    {{ \Str::parse($column->getLabel()) ?: '' }}
                </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @if(count($items))
                @foreach($items as $row)
                <tr>
                    @foreach($columns as $column)
                    <td>
                        {{ ($row)?(is_numeric($column->getValue($row))?$column->getValue($row):\Str::parse($column->getValue($row))):''  }}
                    </td>
                    @endforeach
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</body>
</html>