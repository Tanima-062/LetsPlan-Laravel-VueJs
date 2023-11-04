<table>
    <thead>
    <tr>
        <th>Vorname Kind</th>
        <th>Nachname Kind</th>
        <th>Geschlecht</th>
        <th>Geburtsdatum Kind</th>
        <th>Nationalität Kind</th>
        <th>Muttersprache</th>
        <th>Vorname Mutter</th>
        <th>Nachname Mutter</th>
        <th>Vorname Vater</th>
        <th>Nachname Vater</th>
        <th>Strasse</th>
        <th>Hausnummer</th>
        <th>Postleitzahl</th>
        <th>Ort</th>
        <th>Email</th>
        <th>Telefonnummer</th>
        <th>Mobile Telefonnummer</th>
        <th>Heutige</th>
        <th>Spielgruppe</th>
        <th>Naheliegendste Spielgruppe</th>
        <th>Bemerkungen</th>
        <th>Hergestellt in</th>
        <th>Woche entsprechen</th>
        <th>Planung</th>
        <th>Spielgruppe</th>
        <th>Spielgruppenleiter/in</th>
        <th>Spielgruppenassistent/in</th>
        <th>Planung</th>
        <th>Spielgruppe</th>
        <th>Spielgruppenleiter/in</th>
        <th>Spielgruppenassistent/in</th>
        <th>Planung</th>
        <th>Spielgruppe</th>
        <th>Spielgruppenleiter/in</th>
        <th>Spielgruppenassistent/in</th>
    </tr>
    </thead>
    <tbody>
    @foreach($childrens as $child)
        <tr>
            <td>{{ $child->child_first_name }}</td>
            <td>{{ $child->child_last_name }}</td>
            <td>{{ $child->gender_id == 1 ? 'Junge': 'Mädchen' }}</td>
            <td>{{ $child->child_birthday->format('d.m.Y') }}</td>
            <td>{{ $child->nationality->name??'' }}</td>
            <td>{{ $child->motherTongue->name ??'' }}</td>
            <td>{{ $child->mother_first_name }}</td>
            <td>{{ $child->mother_last_name }}</td>
            <td>{{ $child->father_first_name }}</td>
            <td>{{ $child->father_last_name }}</td>
            <td>{{ $child->street }}</td>
            <td>{{ $child->street_number }}</td>
            <td>{{ $child->postal_code }}</td>
            <td>{{ $child->place }}</td>
            <td>{{ $child->email }}</td>
            <td>{{ $child->phone_number_prefix }} {{$child->phone_number}}</td>
            <td> @if($child->mobile_phone_number)
                    {{ $child->mobile_phone_number_prefix }} {{$child->mobile_phone_number}}
                @endif</td>
            <td>{{ $child->selectedSpielgruppen->name ?? 'Keine' }}</td>
            <td>{{ $child->semester_id }} x wöchentlich</td>
            <td>{{ $child->closestSpielgruppen->name ?? '-' }}</td>
            <td>{{ $child->note }}</td>
            <td>{{ $child->created_at->format('d.m.Y') }}</td>
            <td>
                @foreach($child->slots as $slot)
                    @if($slot->day_id == 1)
                        Montag
                    @elseif($slot->day_id == 2)
                        Dienstag
                    @elseif($slot->day_id == 3)
                        Mittwoch
                    @elseif($slot->day_id == 4)
                        Donnerstag
                    @else
                        Freitag
                    @endif
                    @if($slot->hour_id == 1)
                        - Vormittag ||
                    @else
                        - Nachmittag ||
                    @endif
                @endforeach
            </td>
            @foreach($child->childrenRegistrationOpeningHours as $plan)
                  <td>
                    @if($plan->day_id == 1)
                        Montag
                    @elseif($plan->day_id == 2)
                        Dienstag
                    @elseif($plan->day_id == 3)
                        Mittwoch
                    @elseif($plan->day_id == 4)
                        Donnerstag
                    @else
                        Freitag
                    @endif
                    @if($plan->hour_id == 1)
                        - Vormittag
                    @else
                        - Nachmittag
                    @endif
                  </td>
                    @if($plan->spielgruppen)
                        <td>{{ $plan->spielgruppen->name}}</td>
                        @php
                            $open_hour = \App\Models\OpeningHour::where('hour_id',$plan->hour_id)->where('day_id',$plan->day_id)->where('spielgruppen_id',$plan->spielgruppen->id)->with('lead')->get()->first()
                        @endphp
                        <td>{{ $open_hour ? $open_hour->lead->first_name.' '.$open_hour->lead->last_name : '-' }}</td>
                        <td>{{ $open_hour ? $open_hour->assistant->first_name.' '.$open_hour->assistant->last_name : '-' }}</td>
                    @endif

            @endforeach

        </tr>
    @endforeach
    </tbody>
</table>
