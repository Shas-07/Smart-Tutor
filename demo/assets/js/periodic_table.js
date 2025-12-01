// Periodic Table Data and Display
const periodicTableData = [
    // Period 1
    { number: 1, symbol: 'H', name: 'Hydrogen', weight: '1.008', category: 'nonmetal', group: 1, period: 1 },
    { number: 2, symbol: 'He', name: 'Helium', weight: '4.003', category: 'noble-gas', group: 18, period: 1 },
    
    // Period 2
    { number: 3, symbol: 'Li', name: 'Lithium', weight: '6.941', category: 'alkali-metal', group: 1, period: 2 },
    { number: 4, symbol: 'Be', name: 'Beryllium', weight: '9.012', category: 'alkaline-earth', group: 2, period: 2 },
    { number: 5, symbol: 'B', name: 'Boron', weight: '10.81', category: 'metalloid', group: 13, period: 2 },
    { number: 6, symbol: 'C', name: 'Carbon', weight: '12.01', category: 'nonmetal', group: 14, period: 2 },
    { number: 7, symbol: 'N', name: 'Nitrogen', weight: '14.01', category: 'nonmetal', group: 15, period: 2 },
    { number: 8, symbol: 'O', name: 'Oxygen', weight: '16.00', category: 'nonmetal', group: 16, period: 2 },
    { number: 9, symbol: 'F', name: 'Fluorine', weight: '19.00', category: 'halogen', group: 17, period: 2 },
    { number: 10, symbol: 'Ne', name: 'Neon', weight: '20.18', category: 'noble-gas', group: 18, period: 2 },
    
    // Period 3
    { number: 11, symbol: 'Na', name: 'Sodium', weight: '22.99', category: 'alkali-metal', group: 1, period: 3 },
    { number: 12, symbol: 'Mg', name: 'Magnesium', weight: '24.31', category: 'alkaline-earth', group: 2, period: 3 },
    { number: 13, symbol: 'Al', name: 'Aluminum', weight: '26.98', category: 'metal', group: 13, period: 3 },
    { number: 14, symbol: 'Si', name: 'Silicon', weight: '28.09', category: 'metalloid', group: 14, period: 3 },
    { number: 15, symbol: 'P', name: 'Phosphorus', weight: '30.97', category: 'nonmetal', group: 15, period: 3 },
    { number: 16, symbol: 'S', name: 'Sulfur', weight: '32.07', category: 'nonmetal', group: 16, period: 3 },
    { number: 17, symbol: 'Cl', name: 'Chlorine', weight: '35.45', category: 'halogen', group: 17, period: 3 },
    { number: 18, symbol: 'Ar', name: 'Argon', weight: '39.95', category: 'noble-gas', group: 18, period: 3 },
    
    // Period 4 - Transition metals
    { number: 19, symbol: 'K', name: 'Potassium', weight: '39.10', category: 'alkali-metal', group: 1, period: 4 },
    { number: 20, symbol: 'Ca', name: 'Calcium', weight: '40.08', category: 'alkaline-earth', group: 2, period: 4 },
    { number: 21, symbol: 'Sc', name: 'Scandium', weight: '44.96', category: 'transition-metal', group: 3, period: 4 },
    { number: 22, symbol: 'Ti', name: 'Titanium', weight: '47.87', category: 'transition-metal', group: 4, period: 4 },
    { number: 23, symbol: 'V', name: 'Vanadium', weight: '50.94', category: 'transition-metal', group: 5, period: 4 },
    { number: 24, symbol: 'Cr', name: 'Chromium', weight: '52.00', category: 'transition-metal', group: 6, period: 4 },
    { number: 25, symbol: 'Mn', name: 'Manganese', weight: '54.94', category: 'transition-metal', group: 7, period: 4 },
    { number: 26, symbol: 'Fe', name: 'Iron', weight: '55.85', category: 'transition-metal', group: 8, period: 4 },
    { number: 27, symbol: 'Co', name: 'Cobalt', weight: '58.93', category: 'transition-metal', group: 9, period: 4 },
    { number: 28, symbol: 'Ni', name: 'Nickel', weight: '58.69', category: 'transition-metal', group: 10, period: 4 },
    { number: 29, symbol: 'Cu', name: 'Copper', weight: '63.55', category: 'transition-metal', group: 11, period: 4 },
    { number: 30, symbol: 'Zn', name: 'Zinc', weight: '65.38', category: 'transition-metal', group: 12, period: 4 },
    { number: 31, symbol: 'Ga', name: 'Gallium', weight: '69.72', category: 'metal', group: 13, period: 4 },
    { number: 32, symbol: 'Ge', name: 'Germanium', weight: '72.64', category: 'metalloid', group: 14, period: 4 },
    { number: 33, symbol: 'As', name: 'Arsenic', weight: '74.92', category: 'metalloid', group: 15, period: 4 },
    { number: 34, symbol: 'Se', name: 'Selenium', weight: '78.96', category: 'nonmetal', group: 16, period: 4 },
    { number: 35, symbol: 'Br', name: 'Bromine', weight: '79.90', category: 'halogen', group: 17, period: 4 },
    { number: 36, symbol: 'Kr', name: 'Krypton', weight: '83.80', category: 'noble-gas', group: 18, period: 4 },
    
    // Common elements only - simplified version
    { number: 47, symbol: 'Ag', name: 'Silver', weight: '107.87', category: 'transition-metal', group: 11, period: 5 },
    { number: 79, symbol: 'Au', name: 'Gold', weight: '196.97', category: 'transition-metal', group: 11, period: 6 },
    { number: 80, symbol: 'Hg', name: 'Mercury', weight: '200.59', category: 'transition-metal', group: 12, period: 6 },
    { number: 82, symbol: 'Pb', name: 'Lead', weight: '207.2', category: 'metal', group: 14, period: 6 },
    { number: 92, symbol: 'U', name: 'Uranium', weight: '238.03', category: 'actinide', group: 3, period: 7 },
];

// Grid positions (simplified - showing main elements)
const gridPositions = {
    1: { row: 1, col: 1 }, 2: { row: 1, col: 18 },
    3: { row: 2, col: 1 }, 4: { row: 2, col: 2 }, 5: { row: 2, col: 13 }, 6: { row: 2, col: 14 }, 7: { row: 2, col: 15 }, 8: { row: 2, col: 16 }, 9: { row: 2, col: 17 }, 10: { row: 2, col: 18 },
    11: { row: 3, col: 1 }, 12: { row: 3, col: 2 }, 13: { row: 3, col: 13 }, 14: { row: 3, col: 14 }, 15: { row: 3, col: 15 }, 16: { row: 3, col: 16 }, 17: { row: 3, col: 17 }, 18: { row: 3, col: 18 },
    19: { row: 4, col: 1 }, 20: { row: 4, col: 2 }, 21: { row: 4, col: 3 }, 22: { row: 4, col: 4 }, 23: { row: 4, col: 5 }, 24: { row: 4, col: 6 }, 25: { row: 4, col: 7 }, 26: { row: 4, col: 8 }, 27: { row: 4, col: 9 }, 28: { row: 4, col: 10 }, 29: { row: 4, col: 11 }, 30: { row: 4, col: 12 }, 31: { row: 4, col: 13 }, 32: { row: 4, col: 14 }, 33: { row: 4, col: 15 }, 34: { row: 4, col: 16 }, 35: { row: 4, col: 17 }, 36: { row: 4, col: 18 },
};

document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('periodicTable');
    const info = document.getElementById('elementInfo');
    
    // Create grid cells
    for (let row = 1; row <= 7; row++) {
        for (let col = 1; col <= 18; col++) {
            const cell = document.createElement('div');
            cell.className = 'empty-cell';
            cell.style.gridRow = row;
            cell.style.gridColumn = col;
            table.appendChild(cell);
        }
    }
    
    // Place elements
    periodicTableData.forEach(element => {
        const pos = gridPositions[element.number];
        if (pos) {
            const cell = document.createElement('div');
            cell.className = `element ${element.category}`;
            cell.style.gridRow = pos.row;
            cell.style.gridColumn = pos.col;
            cell.innerHTML = `
                <span class="element-number">${element.number}</span>
                <span class="element-symbol">${element.symbol}</span>
                <span class="element-name">${element.name}</span>
                <span class="element-weight">${element.weight}</span>
            `;
            
            cell.addEventListener('click', () => showElementInfo(element));
            table.appendChild(cell);
        }
    });
    
    function showElementInfo(element) {
        info.innerHTML = `
            <h3>${element.name} (${element.symbol})</h3>
            <div class="element-details">
                <div class="detail-item">
                    <strong>Atomic Number:</strong>
                    <span>${element.number}</span>
                </div>
                <div class="detail-item">
                    <strong>Atomic Weight:</strong>
                    <span>${element.weight} u</span>
                </div>
                <div class="detail-item">
                    <strong>Category:</strong>
                    <span>${element.category.replace('-', ' ')}</span>
                </div>
                <div class="detail-item">
                    <strong>Group:</strong>
                    <span>${element.group}</span>
                </div>
                <div class="detail-item">
                    <strong>Period:</strong>
                    <span>${element.period}</span>
                </div>
            </div>
        `;
    }
});

