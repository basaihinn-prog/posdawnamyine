<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

.table-header {
    text-align: center;
    margin: 20px 0;
}

.table-header h4 {
    font-size: 18px;
    font-weight: 600;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    table-layout: fixed;
}

thead tr {
    background-color: #f1f1f1;
}

th, td {
    padding: 8px 10px;
    text-align: left;
    border: 1px solid #ddd;
    word-wrap: break-word;
    font-size: 14px;
}

th {
    font-weight: 600;
}

.table-img {
    height: 50px;
    width: 50px;
    object-fit: contain;
    border-radius: 4px;
}

/* Responsive adjustments */
@media only screen and (max-width: 768px) {
    th, td {
        font-size: 12px;
        padding: 6px 8px;
    }

    .table-img {
        height: 40px;
        width: 40px;
    }

    .table-header h4 {
        font-size: 16px;
    }
}

@media only screen and (max-width: 480px) {
    th, td {
        font-size: 10px;
        padding: 4px 6px;
    }

    .table-img {
        height: 30px;
        width: 30px;
    }

    .table-header h4 {
        font-size: 14px;
    }
}

</style>
