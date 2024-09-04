<table>
    <caption>My orders</caption>
    <thead>
    <tr>
        <th scope="col">Order ID</th>
        <th scope="col">Full price</th>
        <th scope="col">Product ID</th>
        <th scope="col">Count</th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($orderData)): ?>
        <?php /** @var $data \Entity\OrderItem */ ?>
        <?php foreach ($orderData as $data): ?>
            <tr>
                <td data-label="Number of Order"><?= htmlspecialchars($data->getOrderId()) ?></td>
                <td data-label="Full price"><?= htmlspecialchars($data->getPrice()) ?></td>
                <td data-label="Product ID"><?= htmlspecialchars($data->getProductId()) ?></td>
                <td data-label="Count"><?= htmlspecialchars($data->getCount()) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="4">No orders found</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>

<style>
    table {
        border: 1px solid #ccc;
        border-collapse: collapse;
        margin: 0;
        padding: 0;
        width: 100%;
        table-layout: fixed;
    }

    table caption {
        font-size: 1.5em;
        margin: .5em 0 .75em;
    }

    table tr {
        background-color: #f8f8f8;
        border: 1px solid #ddd;
        padding: .35em;
    }

    table th,
    table td {
        padding: .625em;
        text-align: center;
    }

    table th {
        font-size: .85em;
        letter-spacing: .1em;
        text-transform: uppercase;
    }

    @media screen and (max-width: 600px) {
        table {
            border: 0;
        }

        table caption {
            font-size: 1.3em;
        }

        table thead {
            border: none;
            clip: rect(0 0 0 0);
            height: 1px;
            margin: -1px;
            overflow: hidden;
            padding: 0;
            position: absolute;
            width: 1px;
        }

        table tr {
            border-bottom: 3px solid #ddd;
            display: block;
            margin-bottom: .625em;
        }

        table td {
            border-bottom: 1px solid #ddd;
            display: block;
            font-size: .8em;
            text-align: right;
        }

        table td::before {
            content: attr(data-label);
            float: left;
            font-weight: bold;
            text-transform: uppercase;
        }

        table td:last-child {
            border-bottom: 0;
        }
    }

    /* general styling */
    body {
        font-family: "Open Sans", sans-serif;
        line-height: 1.25;
    }
</style>
