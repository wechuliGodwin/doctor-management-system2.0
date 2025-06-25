<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peer-Reviewed Publications</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>

.breadcrumb-nav {
            background-image: url('   https://kijabehospital.or.ke//guidelines/images/researchimg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            font-weight: 600;
            font-size: 1.25rem; 
            padding: 20px; /* Adjust padding to increase height */
            height: 400px; /* Set a specific height */
            display: flex; /* Use flexbox to center text vertically */
            align-items: center; /* Center text vertically */
        }


        </style>
</head>
<body class="bg-gray-100 text-gray-900">

    <!-- Breadcrumb Navigation -->

  

    <nav class="bg-gray-200 p-3 breadcrumb-nav">
        <div class="container mx-auto text-sm">
            <span class="font-bold" style="font-size: 2.5rem; color: white;">Kijabe Hospital Publications</span>
        </div>
    </nav>
    
    

    <div class="container mx-auto mt-6 flex">
        <!-- Main Content -->
        <div class="w-3/4">
            <h1 class="text-4xl font-bold mb-6">RESEARCH PUBLICATIONS</h1>

            <!-- Publications Section -->
            <div id="publications" class="space-y-12"></div>
        </div>

        <!-- Sidebar -->
        <aside class="w-1/4 ml-8">
            <div class="bg-gray-200 p-4 rounded-lg space-y-2">
                <h2 class="text-xl font-semibold">Evaluation & Research</h2>
                <ul class="space-y-2">
                    <li><a href="#" class="text-blue-600 hover:underline">Evaluation & Research</a></li>
                    <li><a href="#" class="text-blue-600 hover:underline">Publications</a></li>
                    <li><a href="#" class="text-blue-600 hover:underline">Abstracts</a></li>
                    <li><a href="#" class="text-blue-600 hover:underline">Studies</a></li>
                    <li><a href="#" class="text-blue-600 hover:underline">Submissions</a></li>
                </ul>
            </div>
        </aside>
    </div>

    <script>
    const citations = [
    // 2024 Citations
    {
        year: 2024,
        authors: "Linley K.",
        title: "Family Health Clinical Officers: Key professionals to strengthen primary healthcare in Kenya.",
        journal: "Afr J Prim Health Care Fam Med.",
        date: "2024 Jul 29",
        volume: "16(1)",
        pages: "e1-e3",
        doi: "10.4102/phcfm.v16i1.4594",
        pmid: "39099276",
        pmcid: "PMC11304214",
        link: "link"
    },
    {
        year: 2024,
        authors: "Javid PJ, Joharifard S, Nyagetuba MJK, Hansen EN.",
        title: "A time out for prayer.",
        journal: "World J Surg.",
        date: "2024 Mar 19",
        doi: "10.1002/wjs.12149",
        pmid: "38502096",
        link: "link"
    },
    {
        year: 2024,
        authors: "Prasad K, Peterson N, Nolen D, Macharia C, Mannion K, Rohde S, Sinard R.",
        title: "Building a sustainable free flap program in a resource-limited setting: A 12-year humanitarian effort.",
        journal: "Head Neck.",
        date: "2024 May",
        volume: "46(5)",
        pages: "1051-1055",
        doi: "10.1002/hed.27640",
        pmid: "38233973",
        link: "link"
    },
    {
        year: 2024,
        authors: "Nthumba PM, Oundoh LN.",
        title: "Glomus Tumors: A Systematic Review of the Sub-Saharan Africa Experience.",
        journal: "Plast Reconstr Surg Glob Open.",
        date: "2024 Feb 1",
        volume: "12(2)",
        pages: "e5564",
        doi: "10.1097/GOX.0000000000005564",
        pmid: "38313595",
        pmcid: "PMC10833630",
        link: "link"
    },
    {
        year: 2024,
        authors: "Anito MF, Desalegn M, Novotny NM, Hansen EN.",
        title: "Moral injury in healthcare: A low-and-middle-income perspective.",
        journal: "World J Surg.",
        date: "2024 Apr 1",
        doi: "10.1002/wjs.12148",
        pmid: "38558222",
        link: "link"
    },
    {
        year: 2024,
        authors: "Nthumba PM.",
        title: "Global Surgery: The Challenges and Strategies to Win a War That Must Be Won.",
        journal: "Plast Reconstr Surg Glob Open.",
        date: "2024 Jul 3",
        volume: "12(7)",
        pages: "e5953",
        doi: "10.1097/GOX.0000000000005953",
        pmid: "38962157",
        pmcid: "PMC11221857",
        link: "link"
    },
    {
        year: 2024,
        authors: "Malapati SH, Edelen MO, Nthumba PM, Ranganathan K, Pusic AL.",
        title: "Barriers to the Use of Patient-Reported Outcome Measures in Low- and Middle-income Countries.",
        journal: "Plast Reconstr Surg Glob Open.",
        date: "2024 Feb 5",
        volume: "12(2)",
        pages: "e5576",
        doi: "10.1097/GOX.0000000000005576",
        pmid: "38317651",
        pmcid: "PMC10843469",
        link: "link"
    },
    {
        year: 2024,
        authors: "Waweru PK, Yulu E, Matuja SS, Gatimu SM.",
        title: "UPESI: Swahili translation of the FAST acronym for stroke awareness campaigns in East Africa.",
        journal: "Afr J Emerg Med.",
        date: "2024 Sep",
        volume: "14(3)",
        pages: "141-144",
        doi: "10.1016/j.afjem.2024.05.003",
        pmid: "38974391",
        pmcid: "PMC11226961",
        link: "link"
    },
    {
        year: 2024,
        authors: "Nthumba PM, Odhiambo M, Pusic A, Kamau S, Rohde C, Onyango O, Gosman A, Vyas R, Nthumba MN.",
        title: "The State of Surgical Research in Sub-Saharan Africa: An Urgent Call for Surgical Research Trainers.",
        journal: "Plast Reconstr Surg Glob Open.",
        date: "2024 Jun 14",
        volume: "12(6)",
        pages: "e5903",
        doi: "10.1097/GOX.0000000000005903",
        pmid: "38881962",
        pmcid: "PMC11177832",
        link: "link"
    },
    {
        year: 2024,
        authors: "Xiao C, Gebremariam NE, Nthumba P.",
        title: "The Use of the Pedicled Nonislanded Foot Fillet Flap to Avoid an Above-the-Knee Amputation after Trauma.",
        journal: "Plast Reconstr Surg Glob Open.",
        date: "2024 Aug 27",
        volume: "12(8)",
        pages: "e6070",
        doi: "10.1097/GOX.0000000000006070",
        pmid: "39206211",
        pmcid: "PMC11350333",
        link: "link"
    },
    {
        year: 2024,
        authors: "Yousef Y, Cairo S, St-Louis E, Goodman LF, Hamad DM, Baird R, Smith ER, Emil S, Laberge JM, Abdelmalak M, Gathuy Z, Evans F, Adel MG, Bertille KK, Chitnis M, Millano L, Nthumba P, d'Agostino S, Cigliano B, Zea-Salazar L, Ameh E, Ozgediz D, Guadagno E, Poenaru D.",
        title: "GAPS phase II: development and pilot results of the global assessment in pediatric surgery, an evidence-based pediatric surgical capacity assessment tool for low-resource settings.",
        journal: "Pediatr Surg Int.",
        date: "2024 Jun 19",
        volume: "40(1)",
        pages: "158",
        doi: "10.1007/s00383-024-05741-w",
        pmid: "38896255",
        link: "link"
    },
    // 2023 Citations
    {
        year: 2023,
        authors: "Kauffmann RM, Walters C.",
        title: "Bidirectional Medical Training: Legislative Advocacy and a Step Towards Equity in Global Health Education.",
        journal: "Ann Surg.",
        date: "2023 Dec 1",
        volume: "278(6)",
        pages: "e1154-e1155",
        doi: "10.1097/SLA.0000000000005964",
        epub_date: "2023 Jun 22",
        pmid: "37343045",
        link: "link"
    },
    {
        year: 2023,
        authors: "Halestrap P, Aliba D, Otieno G, Brotherton BJ, Gitura HW, Matson JE, Lee BW, Mbugua E.",
        title: "Development and delivery of a higher diploma in emergency medicine and critical care for clinical officers in Kenya.",
        journal: "Afr J Emerg Med.",
        date: "2023 Dec",
        volume: "13(4)",
        pages: "225-229",
        doi: "10.1016/j.afjem.2023.08.006",
        epub_date: "2023 Sep 5",
        pmid: "37701728",
        pmcid: "PMC10494305",
        link: "link"
    },
    {
        year: 2023,
        authors: "Mutua D, Omotola A, Bonilla M, Bhakta N, Friedrich P, Wata D, Muma SN, Ganey M, Muriithi C, Mwangi M, Maina AK, Libes J.",
        title: "Implementation of a formalized evaluation and planning tool to improve pediatric oncology outcomes in Kenya.",
        journal: "Pediatr Blood Cancer.",
        date: "2023 Dec",
        volume: "70(12)",
        pages: "e30657",
        doi: "10.1002/pbc.30657",
        epub_date: "2023 Sep 10",
        pmid: "37690982",
        link: "link"
    },
    {
        year: 2023,
        authors: "Kamita M, Bird P, Akinyi T, Muriithi C, Mugambi A, Satiani B.",
        title: "Evaluation of a mentored surgical training program in Kenya: Improving access to surgical care for vulnerable populations.",
        journal: "Surgery.",
        date: "2023 Jun",
        volume: "193(6)",
        pages: "1200-1208",
        doi: "10.1016/j.surg.2023.03.025",
        epub_date: "2023 Mar 18",
        pmid: "37069103",
        link: "link"
    },
    {
        year: 2023,
        authors: "Rukundo C, Abdu Y, Abubakar M, Cherutich R, Ochola S, Mugenda O.",
        title: "Innovative surgical outreach in perioperative care in low-resource settings.",
        journal: "BMC Health Serv Res.",
        date: "2023 Jun 20",
        volume: "23(1)",
        pages: "574",
        doi: "10.1186/s12913-023-08379-4",
        pmid: "37394367",
        pmcid: "PMC10299360",
        link: "link"
    }
];

// Example function to display citations
function displayCitations(citations) {
    citations.forEach(citation => {
        console.log(`${citation.authors} (${citation.year}). ${citation.title} ${citation.journal} ${citation.date}. ${citation.volume ? citation.volume + ' ' : ''}${citation.pages ? citation.pages : ''} DOI: ${citation.doi} (PMID: ${citation.pmid})`);
    });
}

// Call the function to display citations
displayCitations(citations);


        function displayCitations() {
            const publicationsDiv = document.getElementById('publications');
            const years = {};

            // Organize citations by year
            citations.forEach(citation => {
                if (!years[citation.year]) {
                    years[citation.year] = [];
                }
                years[citation.year].push(citation);
            });

            // Create HTML structure for each year
            for (const [year, citations] of Object.entries(years)) {
                const yearDiv = document.createElement('div');
                yearDiv.innerHTML = `<h2 class="text-2xl font-bold text-gray-700 mb-4">${year}</h2>`;
                const ul = document.createElement('ul');
                ul.classList.add('space-y-4');

                citations.forEach((citation, index) => {
                    const li = document.createElement('li');
                    li.innerHTML = `
                        <span class="font-bold">${index + 1}. </span>
                        <a href="#" class="text-blue-600 hover:underline font-semibold">
                            ${citation.authors} ${citation.title} ${citation.journal} ${citation.date}; ${citation.volume ? citation.volume + ' ' : ''}${citation.pages ? citation.pages : ''} DOI: ${citation.doi} (PMID: ${citation.pmid} ${citation.pmcid ? 'PMCID: ' + citation.pmcid : ''}).
                        </a>
                    `;
                    ul.appendChild(li);
                });

                yearDiv.appendChild(ul);
                publicationsDiv.appendChild(yearDiv);
            }
        }

        // Call the function to display citations
        displayCitations();
    </script>
</body>
</html>
