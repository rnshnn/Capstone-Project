<script>
                        // Add a click event listener to all print buttons
                        const printButton<?= $i; ?> = document.getElementById('print-button<?= $i; ?>');
                        printButton<?= $i; ?>.addEventListener('click', (e) => {
                            e.preventDefault();
                            // Get the corresponding modal for the clicked button
                            const modalId = `#recordModal<?= $i; ?>`;
                            const modal = document.querySelector(modalId);
                            // Open the modal for the row
                            $(modal).modal('show');

                            // Create a style element for print-specific styles
                            const printStyles = document.createElement('style');
                            printStyles.innerHTML = `
                                    /* Add your custom print styles here */
                                    .receipt {
                                    border: 1px solid #000;
                                    padding: 20px;
                                    margin: 20px;
                                }
                                .logo {
                                    /* Add your logo styling here */
                                    width: 300px;
                                    height: auto;
                                }
                                .header {
                                    text-align: center;
                                    font-size: 24px;
                                    font-weight: bold;
                                    margin-bottom: 20px;
                                }
                                .receipt-content {
                                    border-top: 1px solid #000;
                                    margin-top: 20px;
                                    padding-top: 10px;
                                }
                                .row {
                                    margin-top: 10px;
                                    display: flex;
                                }
                                .label_md6 {
                                    flex: 1;
                                    border-right: 1px solid #000;
                                    padding-right: 10px;
                                    padding-left: 10px;
                                }
                                .additional-info {
                                    font-style: italic;
                                    margin-top: 20px;
                                }
                                .footer {
                                    text-align: center;
                                    margin-top: 20px;
                                }   
                                .qr-code {
                                    text-align: center;
                                    margin-top: 20px;
                                }
                                .qr-code img {
                                    width: 100px;
                                    height: 100px;
                                }
                                `;

                            // Append the print-specific styles to the modal content
                            const printWindow = window.open('', '', 'width=600,height=600');
                            printWindow.document.open();
                            printWindow.document.write('<html><head><title>Print</title></head><body>');
                            printWindow.document.head.appendChild(printStyles);
                            printWindow.document.write(modal.querySelector('.receipt_au').innerHTML);
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();
                            printWindow.print();
                            printWindow.close();
                        });
                        </script>