
document.getElementById('export_csv_btn').addEventListener('click', function() {
    const table = document.getElementById('export_modal_table');
    const skipColumns = ["Action", "Update"]; //add skipping rows
    let csvContent = '';

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




document.getElementById('print_modal_table').addEventListener('click', function() {
    const table = document.getElementById('export_modal_table');
    const skipColumns = ["Delivery Method"]; // Specify the columns to skip

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
                cells[index].style.display = 'none'; 
            }
        });
    }

    const printTable = table.cloneNode(true); 
    const headers = printTable.querySelectorAll('thead th');
    const columnsToSkip = getColumnIndicesToSkip(headers);

    headers.forEach((header, index) => {
        if (columnsToSkip.includes(index)) {
            header.style.display = 'none';
        }
    });

    printTable.querySelectorAll('tbody tr').forEach(row => {
        removeColumns(row, columnsToSkip);
    });

    const tableHTML = printTable.outerHTML;
    const printWindow = window.open('', '', 'height=600,width=800');

    printWindow.document.write('<html><head><title>Print Table</title>');
    printWindow.document.write(
        '<style>table { border-collapse: collapse; width: 100%; } th, td { border: 1px solid black; padding: 8px; text-align: left; } th { background-color: #f2f2f2; } .print-title { text-align: center; font-size: 24px; margin-bottom: 10px; } .print-date { text-align: center; font-size: 12px; margin-bottom: 20px; }</style>'
    );
    printWindow.document.write('</head><body>');
    
  
    printWindow.document.write('<div class="print-title"><strong>Karuda Computers</strong></div>');

    
    const options = { timeZone: 'Asia/Kolkata', year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric' };
    const currentDateTime = new Date().toLocaleString('en-IN', options);
   
    printWindow.document.write('<div class="print-date">' + currentDateTime + '</div>');
    printWindow.document.write('<hr>');
    printWindow.document.write(tableHTML);
    printWindow.document.write('</body></html>');
    printWindow.document.close();

    printWindow.onload = function() {
        printWindow.focus();
        printWindow.print();
    };
});



document.getElementById('download_pdf_table').addEventListener('click', function() {
    html2canvas(document.querySelector("#export_modal_table"), { useCORS: true }).then(canvas => {
        const imgData = canvas.toDataURL('image/png');
        const pdf = new jsPDF();
        const imgWidth = 190; // Adjust as needed
        const pageHeight = pdf.internal.pageSize.height;
        const imgHeight = (canvas.height * imgWidth) / canvas.width;
        let heightLeft = imgHeight;

        // Get current date and time in Indian format
        const options = { timeZone: 'Asia/Kolkata', year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric' };
        const currentDateTime = new Date().toLocaleString('en-IN', options);

        // Add the title to the PDF
        pdf.setFontSize(24);
        const title = "Karuda Computers";
        const titleWidth = pdf.getTextWidth(title);
        const x = (pdf.internal.pageSize.width - titleWidth) / 2; // Center the title
        pdf.text(title, x, 20); // Position (x, y)

        pdf.setFontSize(12);
        pdf.text(currentDateTime, x, 30); 

        let position = 40; 

        pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
        heightLeft -= pageHeight;

        while (heightLeft >= 0) {
            position = heightLeft - imgHeight;
            pdf.addPage();
            pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
            heightLeft -= pageHeight;
        }

        pdf.save('table.pdf');
    }).catch(err => {
        console.error('Error generating PDF:', err);
    });
});

