<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rating Bintang</title>
    <style>
        .rating {
            display: inline-block;
            font-size: 0;
            direction: rtl;
        }

        .rating > input {
            display: none;
        }

        .rating > label {
            font-size: 2rem;
            cursor: pointer;
            color: #ddd;
            padding: 0 0.1em;
        }

        .rating > input:checked ~ label,
        .rating:not(:checked) > label:hover,
        .rating:not(:checked) > label:hover ~ label {
            color: gold;
        }

        .rating > input:checked + label:hover,
        .rating > input:checked ~ label:hover,
        .rating > label:hover ~ input:checked ~ label,
        .rating > input:checked ~ label:hover ~ label {
            color: gold;
        }
    </style>
</head>
<body>

    <div class="rating">
        <input type="radio" id="star5" name="rating" value="5" /><label for="star5" title="5 stars">★</label>
        <input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="4 stars">★</label>
        <input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="3 stars">★</label>
        <input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="2 stars">★</label>
        <input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="1 star">★</label>
    </div>

</body>
</html>
