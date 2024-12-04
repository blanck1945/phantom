<head>
    <?php require 'template/metada.php' ?>
</head>

<body>

    <form class="border p-2 py-4 w-1/3 m-auto rounded flex items-center flex-col" action="login" method='POST'>
        <div class="w-full">
            <?php foreach ($page_data['form'] as $field) : ?>
                <?php echo $field ?>
            <?php endforeach; ?>
        </div>
        <button class="bg-green-800 rounded mt-2 h-10 w-1/3 text-white " type="submit">Submit</button>
    </form>

</body>

</html>