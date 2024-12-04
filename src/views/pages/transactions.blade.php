@extends('layout.navbar')

@section('content')

    <body>

        <table>
            <thead>
                <tr>
                    <th><?php echo $page_data['headers'][0]; ?></th>
                    <th><?php echo $page_data['headers'][1]; ?></th>
                    <th><?php echo $page_data['headers'][2]; ?></th>
                    <th><?php echo $page_data['headers'][3]; ?></th>
                </tr>
            </thead>
            <?php foreach ($page_data['csv'] as $row) : ?>
            <tbody>
                <tr>
                    <td><?php echo $row[0]; ?></td>
                    <td><?php echo $row[1]; ?></td>
                    <td><?php echo $row[2]; ?></td>
                    <td class=<?php echo $row[4]; ?>><?php echo $row[3]; ?></td>
                </tr>
            </tbody>
            <?php endforeach; ?>
            <tfoot>
                <tr>
                    <td />
                    <td />
                    <td>Total income</td>
                    <td><?php echo $page_data['total_positive']; ?></td>
                </tr>
                <tr>
                    <td />
                    <td />
                    <td>Total expense</td>
                    <td><?php echo $page_data['total_negative']; ?></td>
                </tr>
                <tr>
                    <td />
                    <td />
                    <td>Net income</td>
                    <td><?php echo $page_data['total']; ?></td>
                </tr>
            </tfoot>
        </table>

    </body>
@endsection
