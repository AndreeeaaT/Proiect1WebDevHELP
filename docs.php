<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctorii noștri</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <header class="bg-dark text-light">
        <div class="container">
            <h1>Doctorii spitalului de urgență "Cardia"</h1>
        </div>
    </header>

    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col-md-12">
                <input type="text" id="search" class="form-control" placeholder="Caută doctori...">
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-3">
                <img src="imagini/doctor1.jpg" alt="Doctor 1" class="img-fluid">
                <h2>Dr. Maria Gheorghe</h2>
                <p>
                    Dr. Maria Gheorghe este un medic specialist cu o experiență de 10 ani în chirurgie cardiacă. Ea este dedicată în totalitate pacienților săi și caută mereu cele mai bune soluții pentru problemele lor de sănătate.
                </p>
                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#feedback1" aria-expanded="false" aria-controls="feedback1">
                    Feedback al Pacienților
                </button>
                <div class="collapse mt-3" id="feedback1">
                    <div class="card card-body">
                        <p>"Dr. Gheorghe m-a ajutat să trec peste cea mai grea perioadă din viața mea. Îi sunt profund recunoscătoare." - Ana M.</p>
                        <p>"Profesionalism și empatie, exact ceea ce aveam nevoie." - Ion C.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <img src="imagini/doctor2.jpg" alt="Doctor 2" class="img-fluid">
                <h2>Dr. Andrei Ionescu</h2>
                <p>
                    Dr. Andrei Ionescu este un radiolog cu o pasiune pentru cercetarea în domeniul neuroștiințelor. Cu o abordare inovatoare și o atenție deosebită la detalii, el își ajută pacienții să își recâștige calitatea vieții.
                </p>
                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#feedback2" aria-expanded="false" aria-controls="feedback2">
                    Feedback al Pacienților
                </button>
                <div class="collapse mt-3" id="feedback2">
                    <div class="card card-body">
                        <p>"Dr. Ionescu este un medic excepțional, mereu atent la nevoile pacienților săi." - Maria P.</p>
                        <p>"Cercetările sale m-au ajutat enorm. Mulțumesc din suflet!" - Gheorghe D.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <img src="imagini/doctor3.jpg" alt="Doctor 3" class="img-fluid">
                <h2>Dr. Mihai Octavian</h2>
                <p>
                    Dr. Mihai Octavian este un pediatru cu o experiență vastă în îngrijirea copiilor și adolescenților. El pune întotdeauna nevoile pacienților săi pe primul loc și se asigură că aceștia primesc tratamentul și atenția de care au nevoie.
                </p>
                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#feedback3" aria-expanded="false" aria-controls="feedback3">
                    Feedback al Pacienților
                </button>
                <div class="collapse mt-3" id="feedback3">
                    <div class="card card-body">
                        <p>"Dr. Octavian a fost extraordinar cu fiul meu. Recomand cu căldură." - Elena F.</p>
                        <p>"Un medic desăvârșit și o persoană minunată." - Vasile R.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <img src="imagini/doctor4.jpg" alt="Doctor 4" class="img-fluid">
                <h2>Dr. Ioana Popa</h2>
                <p>
                    Dr. Ioana Popa este un ginecolog cu o expertiză deosebită în domeniul sănătății femeilor. Ea crede într-o abordare holistică și personalizată pentru fiecare pacient, ajutându-i să-și recâștige sănătatea și viața alături de un nou membru al familiei.
                </p>
                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#feedback4" aria-expanded="false" aria-controls="feedback4">
                    Feedback al Pacienților
                </button>
                <div class="collapse mt-3" id="feedback4">
                    <div class="card card-body">
                        <p>"Dr. Popa mi-a fost alături în timpul sarcinii și am avut o experiență excelentă." - Ioana T.</p>
                        <p>"Un medic dedicat și foarte empatic." - Ana S.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search');
            const doctorCards = document.querySelectorAll('.col-md-3');

            searchInput.addEventListener('input', function() {
                const searchTerm = searchInput.value.toLowerCase();

                doctorCards.forEach(card => {
                    const doctorName = card.querySelector('h2').textContent.toLowerCase();
                    const doctorDescription = card.querySelector('p').textContent.toLowerCase();
                    if (doctorName.includes(searchTerm) || doctorDescription.includes(searchTerm)) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    </script>


    
    <section id="feedback" class="py-5 bg-light">
        <div class="container">
        <section id="feedback" class="py-5 bg-light">
        <div class="container">
            <h2>Feedback al Pacienților</h2>
            <p>Puteți lăsa feedback pentru doctorii noștri folosind formularele de mai jos. Feedback-ul dumneavoastră este foarte important pentru noi.</p>

            <!-- Formular de feedback -->
            <?php
            include "db_conn.php"; // Include fișierul de conexiune la baza de date

            // Preia lista doctorilor din baza de date
            $sql = "SELECT id, nume FROM doctori";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo '<form id="feedback-form" action="feedback.php" method="post">';
                echo '<div class="form-group">';
                echo '<label for="doctor">Doctor:</label>';
                echo '<select class="form-control" id="doctor" name="doctor_id" required>';
                echo '<option value="" selected disabled>Selectează un doctor</option>';
                
                // Populează lista derulantă cu doctorii din baza de date
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row['id'] . '">' . $row['nume'] . '</option>';
                }
                
                echo '</select>';
                echo '</div>';
                echo '<div class="form-group">';
                echo '<label for="name">Nume:</label>';
                echo '<input type="text" class="form-control" id="name" name="name" required>';
                echo '</div>';
                echo '<div class="form-group">';
                echo '<label for="feedback">Feedback:</label>';
                echo '<textarea class="form-control" id="feedback" name="feedback" rows="3" required></textarea>';
                echo '</div>';
                echo '<button type="submit" class="btn btn-primary">Trimite</button>';
                echo '</form>';
            } else {
                echo '<p>Nu există doctori în baza de date.</p>';
            }

            $conn->close();
            ?>
        </div>
    </section>
        </div>
    </section>
</body>
</html>
