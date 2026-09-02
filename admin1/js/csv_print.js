
let currentFilter = ''; 

document.getElementById('export_table').addEventListener('click', function() {
    const table = document.getElementById('example1');
 
    const skipColumns = ["Action", "Update", "Download Invoice"];
    let csvContent = '';
 // Get the current date and time
 const currentDate = new Date().toLocaleDateString();
 const currentTime = new Date().toLocaleTimeString();
    function escapeCSV(value) {
        if (value.includes('"') || value.includes(',') || value.includes('\n')) {
            value = `"${value.replace(/"/g, '""')}"`;
        }
        return value;
    }
    // Prepare header
    const headers = table.querySelectorAll('thead th');
    const headerIndices = [];
    csvContent +=  currentDate + ' -' + currentTime + '\n\n';
    csvContent += 'Karuda Computers\n';
    csvContent += sessionTitle + '\n';
    csvContent += 'Order Reports\n';
    const filterTitle = currentFilter ? `${currentFilter.charAt(0).toUpperCase() + currentFilter.slice(1)} Orders` : '';
    csvContent += `${filterTitle}\n\n`;

    headers.forEach((header, index) => {
        if (!skipColumns.includes(header.textContent.trim())) {
            headerIndices.push(index);
            csvContent += escapeCSV(header.textContent.trim()) + ',';
        }
    });
    csvContent = csvContent.slice(0, -1) + '\n'; 
    const tableInstance = $('#example1').DataTable();
    const originalLength = tableInstance.page.len();
    tableInstance.page.len(-1).draw(); 

    table.querySelectorAll('tbody tr').forEach(row => {
        let rowData = [];
        let cells = row.querySelectorAll('td');
        headerIndices.forEach(index => {
            if (cells[index]) {
                rowData.push(escapeCSV(cells[index].textContent.trim()));
            }
        });
        csvContent += rowData.join(',') + '\n';
    });
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'table.csv';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    tableInstance.page.len(originalLength).draw(); 
});


document.getElementById('print_table').addEventListener('click', function() {
    const table = document.getElementById('example1');
    const skipColumns = ["Delivery Method", "Action", "View", "View Reviews", "Change Status"];

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

    const tableInstance = $('#example1').DataTable();
    const originalLength = tableInstance.page.len();
    tableInstance.page.len(-1).draw(); // Show all rows

    const printTable = table.cloneNode(true); // Clone the table for printing
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

    const printWindow = window.open('', '', 'height=600,width=800');
    const tableHTML = printTable.outerHTML;

    printWindow.document.write('<html><head><title>Print Table</title>');
    printWindow.document.write('<style>table { border-collapse: collapse; width: 100%; } th, td { border: 1px solid black; padding: 4px; text-align: left; font-size: 10px; } th { background-color: #f2f2f2; }</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write('<h1 style="text-align: center;">Karuda Computers - ' + sessionTitle + '</h1>'); // Insert session title
    printWindow.document.write(tableHTML);
    printWindow.document.write('</body></html>');
    printWindow.document.close();

    printWindow.onload = function() {
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    };

    tableInstance.page.len(originalLength).draw();
});



document.getElementById('download_pdf').addEventListener('click', function () {
    const { jsPDF } = window.jspdf; // Load jsPDF
    const doc = new jsPDF(); // Create a new jsPDF instance

    const buttons = document.querySelectorAll('.content-header-right button');

    // Function to hide buttons and their text
    function hideButtons() {
        buttons.forEach(button => {
            button.setAttribute('data-original-text', button.innerText); // Store the original text
            button.innerText = ''; // Clear the button text
        });
    }

    // Function to show buttons and restore their text
    function showButtons() {
        buttons.forEach(button => {
            button.style.visibility = 'visible'; // Restore visibility
            button.innerText = button.getAttribute('data-original-text'); // Restore the original text
        });
    }

    // Hide the buttons before generating the PDF
    hideButtons();

    // Temporarily disable pagination
    const table = $('#example1').DataTable();
    const currentPageLength = table.page.len(); // Store the current page length
    const currentPage = table.page(); // Store the current page index

    // Set the page length to a large number to show all records
    table.page.len(-1).draw(); // Disable pagination

    // Extract table headers
    const headers = [];
    const unwantedHeaders = ["Action", "Change Status", "View","Category Image","Category Icon",]; // Define the headers to skip

    document.querySelectorAll('#example1 thead th').forEach(th => {
        if (!unwantedHeaders.includes(th.innerText.trim())) { // Check if the header is not in the unwanted headers
            headers.push(th.innerText); // Collect the text of each header
        }
    });

    // Extract all table data (rows)
    const rows = [];
    const allRows = document.querySelectorAll('#example1 tbody tr'); // Get all rows in the tbody
    allRows.forEach(tr => {
        const rowData = [];
        tr.querySelectorAll('td').forEach((td, index) => {
            // Check if the cell contains an anchor tag
            const anchor = td.querySelector('a');
            const headerText = document.querySelector(`#example1 thead th:nth-child(${index + 1})`).innerText.trim(); // Get corresponding header text
            
            // Skip unwanted columns and cells with anchor tags
            if (!anchor && !unwantedHeaders.includes(headerText)) { 
                // If there's no anchor, add the plain text
                rowData.push(td.innerText);
            } 
        });
        rows.push(rowData); // Add row data to the rows array
    });

    // Get the page width to center the title
    const pageWidth = doc.internal.pageSize.getWidth();
    const centerTitlePosition = pageWidth / 2;

    // Add titles before the table in the PDF
    doc.setFontSize(18);
    doc.text('Karuda Computers', centerTitlePosition, 20, { align: 'center' });  // Static title "Karuda Computers" aligned to center

    // Add session title
    doc.setFontSize(16);
    doc.text(sessionTitle, centerTitlePosition, 30, { align: 'center' });  // Dynamic title from session aligned to center

    // Add current date
    const currentDate = new Date().toLocaleDateString();
    doc.setFontSize(12);
    doc.text(`Date: ${currentDate}`, centerTitlePosition, 40, { align: 'center' });  // Center the current date

    // Add the extracted table data to the PDF
    doc.autoTable({
        startY: 50,  // Start the table below the titles and date
        head: [headers],  
        body: rows,       
        theme: 'grid',    // Use a grid theme for better structure
    });

    // Save the generated PDF with a name
    doc.save('customer_details.pdf'); // Save the file as 'customer_details.pdf'

    // Restore pagination settings
    table.page.len(currentPageLength).draw(); // Restore the original page length
    table.page(currentPage).draw(); // Restore the original page

    // Show the buttons again after generating the PDF
    showButtons();
});
