<?php

if (isset($_GET['property_id']) && !empty($_GET['property_id']) && is_numeric($_GET['property_id'])) {

    $sql = "SELECT * FROM properties 
            JOIN users ON users.id = properties.landlord_id 
            WHERE property_id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('i', $_GET['property_id']);
    $stmt->execute();
    $property = $stmt->get_result();
    $stmt->close();
} else {
    header("Location: /");
}

?>
<?php require './partials/header.inc.php' ?>
<section class="section">
    <div class="container">
        <h1 class="title">Properties</h1>
        <div class="flex-grid">
            <?php if ($property->num_rows == 0) : ?>
                <div class="cell"> Property not found </div>
            <?php else :
                $property = $property->fetch_assoc();
                $min_ammount = $property['rent'] / $property['tenancy'];
            ?>
                <div class="grid">
                    <div class="card">
                        <div class="card-image">
                            <figure class="image is-4by3">
                                <img src="/storage/placeholder.jpg" alt="Placeholder image" />
                            </figure>
                        </div>
                        <div class="card-content">
                            <div class="media">
                                <div class="media-left">
                                    <figure class="image is-48x48">
                                        <img src="/storage/avatar.jpg" alt="Placeholder image" />
                                    </figure>
                                </div>
                                <div class="media-content">
                                    <p class="title is-4 is-capitalized"><?= $property['name'] ?></p>
                                    <p class="subtitle is-6"><?= $property['email'] ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-container">
                        <table class="table is-primary table is-fullwidth">
                            <thead>
                                <tr>
                                    <th>Feature</th>
                                    <th>details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Tenancy</td>
                                    <td><?= $property['tenancy'] ?> years</td>
                                </tr>
                                <tr>
                                    <td>Category</td>
                                    <td><?= $property['category'] ?></td>
                                </tr>
                                <tr>
                                    <td>Area</td>
                                    <td><?= $property['area'] ?></td>
                                </tr>
                                <tr>
                                    <td>Rent</td>
                                    <td>$<?= $property['rent'] ?></td>
                                </tr>
                                <tr>
                                    <td>Description</td>
                                    <td><?= $property['description'] ?></td>
                                </tr>
                            </tbody>
                        </table>

                        <form action="/book" method="POST" id="booking-form">
                            <input type="hidden" id="property_id" name="property_id" value="<?= $property['property_id'] ?>">
                            <input type="hidden" id="amount" name="amount" value="0">
                            <input type="hidden" value="<?= $property['tenancy'] * 365 ?>" id="max-tenancy-days">

                            <div class="field">
                                <label class="label" for="start_date">Start Date:</label>
                                <div class="control">
                                    <input class="input" type="date" id="start_date" name="start_date" min="<?= date('Y-m-d') ?>" oninput="calculateRent(<?= $property['rent'] ?>)" required>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label" for="start_date">End Date:</label>
                                <div class="control">
                                    <input class="input" type="date" id="end_date" name="end_date" min="<?= date('Y-m-d') ?>" oninput="calculateRent(<?= $property['rent'] ?>)" required>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label has-text-danger" for="amount_paid" style="float: right;">Amount to pay: $<span id="total">0</span></label>
                            </div>
                            <div class="field">
                                <div class="control">
                                    <input class="button is-primary" type="submit" value="Book Property">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif ?>
        </div>
    </div>
</section>
<?php require './partials/footer.inc.php' ?>