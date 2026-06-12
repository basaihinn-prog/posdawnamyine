
document.addEventListener("DOMContentLoaded", function () {

        document.getElementById("printBtn").addEventListener("click", function () {

            let printContents = document.getElementById("printArea").innerHTML;

            // Open print window
            let printWindow = window.open("", "_blank", "width=900,height=700");

            if (!printWindow) {
                alert("Popup blocked! Please allow popups for this site.");
                return;
            }

            printWindow.document.open();
            printWindow.document.write(`
                <html>
                <head>
                    <title>Invoice Print</title>

                    <link rel="stylesheet" href="${window.APP_ASSETS.bootstrap}">
                    <link rel="stylesheet" href="${window.APP_ASSETS.admin}">

                    <style>
                        body {
                            font-family: "Inter", sans-serif;
                            padding: 0px;
                            margin: 0px;
                        }

                        @media print {
                            body {
                                -webkit-print-color-adjust: exact !important;
                                print-color-adjust: exact !important;
                            }
                                .invoice-container-sm{
                                padding: 0 !important;
                                }
                        }
                    </style>
                </head>
                <body>
                    ${printContents}
                </body>
                </html>
            `);

            printWindow.document.close();

            printWindow.onload = function () {
                printWindow.focus();
                printWindow.print();
            };

        });
});
