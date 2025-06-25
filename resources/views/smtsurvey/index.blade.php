<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>360° Leadership Evaluation Survey</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div id="survey-container" style="display: none;">
    <h1>360° Leadership Evaluation Survey</h1>
    <form id="survey-form" action="{{ route('survey.submit') }}" method="POST">
        @csrf

        <h2>Instructions:</h2>
        <p>This survey is anonymous and aims to assess our organization's leadership effectiveness while also allowing senior leaders to self-reflect on their own understanding and alignment with the hospital's vision and strategy. Responses will be rated on a 5-point Likert scale:</p>
        <ul>
            <li>1 – Strongly Disagree</li>
            <li>2 – Disagree</li>
            <li>3 – Neutral</li>
            <li>4 – Agree</li>
            <li>5 – Strongly Agree</li>
        </ul>
        <p>Some open-ended questions are included for deeper insights.</p>

        {{-- Section 1: CEO Leadership Evaluation --}}
        <h2>CEO Leadership Evaluation</h2>
        @foreach(['Vision & Strategy', 'Decision-Making & Problem-Solving', 'Leadership & People Development', 'Communication & Engagement', 'Organizational Culture & Values'] as $section)
            <h3>{{ $section }}</h3>
            @for($i = 1; $i <= 4; $i++)
                @php
                    $questionNumber = (['Vision & Strategy' => 1, 'Decision-Making & Problem-Solving' => 5, 'Leadership & People Development' => 9, 'Communication & Engagement' => 13, 'Organizational Culture & Values' => 17][$section] + $i - 1);
                @endphp
                <label>
                    {{ $questionNumber }}. {{ $section === 'Vision & Strategy' ? 'The CEO provides a clear and compelling vision for the hospital’s future.' : 
                        ($section === 'Decision-Making & Problem-Solving' ? 'The CEO makes well-informed, ethical, and timely decisions.' : 
                        ($section === 'Leadership & People Development' ? 'The CEO fosters a culture of trust, integrity, and accountability.' : 
                        ($section === 'Communication & Engagement' ? 'The CEO communicates clearly and ensures transparency in decision-making.' : 
                        'The CEO upholds and models the Christian mission and values of the hospital.'))) }}
                    <br>
                    <input type="radio" name="ceo_{{ str_replace(' ', '_', $section) }}_{{ $i }}" value="1" required> 1
                    <input type="radio" name="ceo_{{ str_replace(' ', '_', $section) }}_{{ $i }}" value="2"> 2
                    <input type="radio" name="ceo_{{ str_replace(' ', '_', $section) }}_{{ $i }}" value="3"> 3
                    <input type="radio" name="ceo_{{ str_replace(' ', '_', $section) }}_{{ $i }}" value="4"> 4
                    <input type="radio" name="ceo_{{ str_replace(' ', '_', $section) }}_{{ $i }}" value="5"> 5
                </label><br>
            @endfor
        @endforeach

        {{-- Section 2: Leader’s Self-Evaluation on Alignment with Strategy & Vision --}}
        <h2>Leader’s Self-Evaluation on Alignment with Strategy & Vision</h2>
        @foreach(['Understanding and Alignment', 'Leadership Reflection'] as $section)
            <h3>{{ $section }}</h3>
            @for($i = 1; $i <= (in_array($section, ['Understanding and Alignment']) ? 5 : 3); $i++)
                @php
                    $questionNumber = (['Understanding and Alignment' => 19, 'Leadership Reflection' => 24][$section] + $i - 1);
                @endphp
                <label>
                    {{ $questionNumber }}. {{ $section === 'Understanding and Alignment' ? 'I have a clear understanding of the hospital’s overall vision and strategic direction.' : 
                        ($i == 1 ? 'I believe I am fully engaged in driving the hospital’s strategy forward.' : 
                        ($i == 2 ? 'I regularly communicate and reinforce strategic goals within my team.' : 
                        'I would benefit from more strategic guidance from the CEO or executive leadership.')) }}
                    <br>
                    <input type="radio" name="self_{{ str_replace(' ', '_', $section) }}_{{ $i }}" value="1" required> 1
                    <input type="radio" name="self_{{ str_replace(' ', '_', $section) }}_{{ $i }}" value="2"> 2
                    <input type="radio" name="self_{{ str_replace(' ', '_', $section) }}_{{ $i }}" value="3"> 3
                    <input type="radio" name="self_{{ str_replace(' ', '_', $section) }}_{{ $i }}" value="4"> 4
                    <input type="radio" name="self_{{ str_replace(' ', '_', $section) }}_{{ $i }}" value="5"> 5
                </label><br>
            @endfor
        @endforeach

        {{-- Section 3: Open-Ended Questions --}}
        <h2>Open-Ended Questions (Optional)</h2>
        @for($i = 27; $i <= 30; $i++)
            <label for="open_{{ $i }}">Question {{ $i }}:</label><br>
            <textarea id="open_{{ $i }}" name="open_{{ $i }}" rows="4" cols="50"></textarea><br>
        @endfor

        <input type="submit" value="Submit Survey">
    </form>
</div>

<div id="pass-container">
    <h2>Enter Survey Pass</h2>
    <input type="password" id="survey-pass" placeholder="Enter pass here">
    <button onclick="validatePass()">Submit</button>
</div>

<script>
    function validatePass() {
        const pass = document.getElementById('survey-pass').value;
        if (pass === "Sarova2025") {
            document.getElementById('pass-container').style.display = 'none';
            document.getElementById('survey-container').style.display = 'block';
        } else {
            alert('Incorrect pass. Please try again.');
        }
    }
</script>

</body>
</html>