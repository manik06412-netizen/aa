document.getElementById('export_table').addEventListener('click', function() {
    const table = document.getElementById('example1');
    const skipColumns = ["Action", "Update"];
    let csvContent = '';
    csvContent += 'Karuda Computers\n';
    function escapeCSV(value) {
        if (value.includes('"') || value.includes(',') || value.includes('\n')) {
            value = `"${value.replace(/"/g, '""')}"`;
        }
        return value;
    }

    const headers = table.querySelectorAll('thead th');
    const headerIndices = [];
    headers.forEach((header, index) => {
        if (!skipColumns.includes(header.textContent.trim())) {
            headerIndices.push(index);
            csvContent += escapeCSV(header.textContent.trim()) + ',';
        }
    });
    csvContent = csvContent.slice(0, -1) + '\n'; 

    for (let row of table.querySelectorAll('tbody tr')) {
        let rowData = [];
        let cells = row.querySelectorAll('td');
        headerIndices.forEach(index => {
            if (cells[index]) {
                rowData.push(escapeCSV(cells[index].textContent.trim()));
            }
        });
        csvContent += rowData.join(',') + '\n';
    }

    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });

    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'table.csv';

    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
});


// print table code
document.getElementById('print_table').addEventListener('click', function() {
    const table = document.getElementById('example1');
    const skipColumns = ["Delivery Method"]; 

    function getColumnIndicesToSkip(headers) {
        const skipIndices = [];
        headers.forEach((header, index) => {
            if (skipColumns.includes(header.textContent.trim())) {
                skipIndices.push(index);
            }
        });
        return skipIndices;
    }

    function removeColumns(row, indicesToSkip) {
        const cells = row.querySelectorAll('th, td');
        indicesToSkip.forEach(index => {
            if (cells[index]) {
                cells[index].style.display = 'none'; // Hide the specified columns in print
            }
        });
    }

    const printTable = table.cloneNode(true); // Clone the table for printing

    const headers = printTable.querySelectorAll('thead th');
    const columnsToSkip = getColumnIndicesToSkip(headers);

    // Hide the specified header columns
    headers.forEach((header, index) => {
        if (columnsToSkip.includes(index)) {
            header.style.display = 'none';
        }
    });

    // Hide the specified columns in table rows
    printTable.querySelectorAll('tbody tr').forEach(row => {
        removeColumns(row, columnsToSkip);
    });

    // Prepare print window
    const tableHTML = printTable.outerHTML;
    const printWindow = window.open('', '', 'height=600,width=800');

    printWindow.document.write('<html><head><title>Print Table</title>');
    printWindow.document.write(
        '<style>table { border-collapse: collapse; width: 100%; } th, td { border: 1px solid black; padding: 8px; text-align: left; } th { background-color: #f2f2f2; }</style>'
    );
    printWindow.document.write('</head><body>');
    printWindow.document.write(tableHTML);
    printWindow.document.write('</body></html>');
    printWindow.document.close();

    printWindow.onload = function() {
        printWindow.focus();
        printWindow.print();
    };
});
document.getElementById('download_pdf').addEventListener('click', function() {
    const table = document.querySelector("#example1");
    const originalClass = table.className;
    const actionColumnIndex = Array.from(document.querySelectorAll('#example1 th'))
        .findIndex(th => th.textContent.trim() === 'Action');
    
    const actionButtons = document.querySelectorAll('.action-button-selector'); // Replace with your actual button selector
    const lastColumn = document.querySelectorAll("td:last-child, th:last-child");

    function hideColumn(index) {
        if (index >= 0) {
            document.querySelectorAll(`#example1 th`)[index].style.display = 'none';
            document.querySelectorAll(`#example1 tr`).forEach(row => {
                row.children[index].style.display = 'none';
            });
        }
    }

    hideColumn(actionColumnIndex);
    actionButtons.forEach(button => { button.style.display = 'none'; });

    // Remove the table class and adjust styles for PDF generation
    table.className = '';
    table.style.borderSpacing = '0.5em';
    table.querySelectorAll('th, td').forEach(cell => {
        cell.style.border = '0.1px solid black';
        cell.style.padding = '0.5em';
    });

    html2canvas(table, { backgroundColor: null, useCORS: true }).then(canvas => {
        const imgData = canvas.toDataURL('image/png');
        const pdf = new jsPDF();
        const imgWidth = 190;
        const pageHeight = pdf.internal.pageSize.height;
        const imgHeight = (canvas.height * imgWidth) / canvas.width;
        let heightLeft = imgHeight;
        let currentPage = 1;
        const totalPages = Math.ceil(imgHeight / pageHeight);

        pdf.setTextColor(0, 0, 0);
        pdf.setFontSize(18);
        pdf.text('Karuda Computers', pdf.internal.pageSize.width / 2, 20, null, null, 'center');
        const currentDateTime = new Date().toLocaleString();
        pdf.setFontSize(12);
        pdf.text(`Date: ${currentDateTime}`, 10, 10);
        pdf.setLineWidth(1);
        pdf.rect(5, 5, pdf.internal.pageSize.width - 10, pageHeight - 10);
        pdf.addImage(imgData, 'PNG', 10, 45, imgWidth, imgHeight);
        heightLeft -= (pageHeight - 45);
        pdf.setFontSize(10);
        pdf.text(`Page ${currentPage} of ${totalPages}`, pdf.internal.pageSize.width / 2, pageHeight - 10, null, null, 'center');

        while (heightLeft >= 0) {
            currentPage++;
            pdf.addPage();
            pdf.setLineWidth(1);
            pdf.rect(5, 5, pdf.internal.pageSize.width - 10, pageHeight - 10);
            pdf.addImage(imgData, 'PNG', 10, 10, imgWidth, imgHeight);
            heightLeft -= pageHeight;
            pdf.text(`Page ${currentPage} of ${totalPages}`, pdf.internal.pageSize.width / 2, pageHeight - 10, null, null, 'center');
        }

        // Save the PDF
        pdf.save('table.pdf');

        // Restore the last column visibility after the PDF is generated
        lastColumn.forEach(cell => {
            cell.style.display = ''; // Restore visibility
        });
        restoreButtonsAndColumns(actionColumnIndex, actionButtons);
        table.className = originalClass;
        table.style.borderCollapse = ''; // Reset border collapse
        table.style.borderSpacing = ''; // Reset border spacing
        table.querySelectorAll('th, td').forEach(cell => {
            cell.style.border = ''; // Reset border for cells
            cell.style.padding = ''; // Reset padding for cells
        });
    }).catch(err => {
        console.error('Error generating PDF:', err);
    });
});

function restoreButtonsAndColumns(actionColumnIndex, actionButtons) {
    actionButtons.forEach(button => { button.style.display = ''; });

    if (actionColumnIndex >= 0) {
        document.querySelectorAll(`#example1 th`)[actionColumnIndex].style.display = '';
        document.querySelectorAll(`#example1 tr`).forEach(row => { row.children[actionColumnIndex].style.display = ''; });
    }
}
