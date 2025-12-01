// Parent Dashboard JavaScript - Multi-language support
const translations = {
    en: {
        welcome: 'Welcome',
        parent: 'Parent',
        student_info: 'Student Information',
        student_name: 'Student Name',
        student_id: 'Student ID',
        completed: 'Completed',
        in_progress: 'In Progress',
        not_started: 'Not Started',
        total_materials: 'Total Materials',
        progress_chart: 'Progress Chart',
        detailed_progress: 'Detailed Progress',
        no_progress: 'No progress data available.',
        no_student: 'No student linked to this parent account.',
        calculator: 'Calculator',
        formulas: 'Formulas'
    },
    hi: {
        welcome: 'स्वागत है',
        parent: 'अभिभावक',
        student_info: 'छात्र जानकारी',
        student_name: 'छात्र का नाम',
        student_id: 'छात्र आईडी',
        completed: 'पूर्ण',
        in_progress: 'प्रगति पर',
        not_started: 'शुरू नहीं किया',
        total_materials: 'कुल सामग्री',
        progress_chart: 'प्रगति चार्ट',
        detailed_progress: 'विस्तृत प्रगति',
        no_progress: 'कोई प्रगति डेटा उपलब्ध नहीं है।',
        no_student: 'इस अभिभावक खाते से कोई छात्र लिंक नहीं है।',
        calculator: 'कैलकुलेटर',
        formulas: 'सूत्र'
    },
    es: {
        welcome: 'Bienvenido',
        parent: 'Padre',
        student_info: 'Información del Estudiante',
        student_name: 'Nombre del Estudiante',
        student_id: 'ID del Estudiante',
        completed: 'Completado',
        in_progress: 'En Progreso',
        not_started: 'No Iniciado',
        total_materials: 'Materiales Totales',
        progress_chart: 'Gráfico de Progreso',
        detailed_progress: 'Progreso Detallado',
        no_progress: 'No hay datos de progreso disponibles.',
        no_student: 'No hay estudiante vinculado a esta cuenta de padre.',
        calculator: 'Calculadora',
        formulas: 'Fórmulas'
    },
    fr: {
        welcome: 'Bienvenue',
        parent: 'Parent',
        student_info: 'Informations sur l\'Étudiant',
        student_name: 'Nom de l\'Étudiant',
        student_id: 'ID de l\'Étudiant',
        completed: 'Terminé',
        in_progress: 'En Cours',
        not_started: 'Non Commencé',
        total_materials: 'Matériaux Totaux',
        progress_chart: 'Graphique de Progrès',
        detailed_progress: 'Progrès Détaillé',
        no_progress: 'Aucune donnée de progression disponible.',
        no_student: 'Aucun étudiant lié à ce compte parent.',
        calculator: 'Calculatrice',
        formulas: 'Formules'
    }
};

let currentLanguage = localStorage.getItem('language') || 'en';

document.addEventListener('DOMContentLoaded', function() {
    const languageSelect = document.getElementById('languageSelect');
    
    if (languageSelect) {
        languageSelect.value = currentLanguage;
        languageSelect.addEventListener('change', function() {
            currentLanguage = this.value;
            localStorage.setItem('language', currentLanguage);
            translatePage();
        });
    }
    
    translatePage();
});

function translatePage() {
    const elements = document.querySelectorAll('[data-translate]');
    const lang = translations[currentLanguage] || translations.en;
    
    elements.forEach(element => {
        const key = element.getAttribute('data-translate');
        if (lang[key]) {
            if (element.tagName === 'INPUT' && element.type === 'text') {
                // For input fields, preserve the original value structure
                const originalText = element.textContent || element.value;
                if (originalText.includes('Welcome')) {
                    element.value = lang[key] + ', ' + (element.value.split(', ')[1] || '');
                } else {
                    element.value = lang[key];
                }
            } else {
                const originalText = element.textContent;
                if (originalText.includes('Welcome,')) {
                    element.textContent = lang[key] + ', ' + originalText.split(', ')[1];
                } else {
                    element.textContent = lang[key];
                }
            }
        }
    });
}

