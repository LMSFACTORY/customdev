<?php
$email = $data['email'];
$countryCodes = $data['countryCodes'];
$functions = $data['functions'];
$domain_excellence = $data['domain_excellence'];
$region = $data['region'];
$USER = $data['user'];
$existing_user = $data['existing_user'];
$statut_dossier = [
    'Complet',
    'Incomplet',
    'Indisponible'
];

$statut = [
    'Honoraire',
    'Vacataire',
    'Benevole',
    'Interne INPI'
];
?>


<h1><?php echo $data['title']; ?></h1>

<form action="information_form.php?email=<?php echo $email ?>" method="post">
    <section class="section personal_info">
        <div class="section_header" data-toggle="collapse" data-target="#personalInfoCollapse" style="cursor: pointer;">
            <h4>Identité du prestataire </h4>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="section_body" id="personalInfoCollapse">
            <div class="form-group">
                <label for="email">Courriel</label>
                <input type="email" name="email"
                    class="form-control  <?php echo (!empty($err_data['email_err'])) ? 'is-invalid' : ''; ?>" id="email"
                    aria-describedby="emailHelp" value="<?php echo ($err_data['email_err']) ??
                        $email; ?>" placeholder="Votre Email" readonly="readonly">
                <span class="invalid-feedback"><?php echo $err_data['email_err']; ?></span>
            </div>

            <div class="form-group form-check-inline">
                <label class="form-check-label" for="civilite">Civilité
                </label><br>
                <input type="radio" class="form-check-input" name="civilite" value="M." id="civilite_mr" required
                    <?php echo ($err_data['civilite'] == 'M.') ? 'checked' : ''; ?>>
                <label class="form-check-label" for="civilite_mr">M.</label>
                <input type="radio" class="form-check-input" name="civilite" value="Mme" id="civilite_mrs" required
                    <?php echo ($err_data['civilite'] == 'Mme') ? 'checked' : ''; ?>>
                <label class="form-check-label" for="civilite_mrs">Mme</label>
                <span class="invalid-feedback"><?php echo $err_data['civilite_err']; ?></span>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="firstname">Prénom
                    </label>
                    <input type="text" name="firstname"
                        class="form-control <?php echo (!empty($err_data['firstname_err'])) ? 'is-invalid' : ''; ?>"
                        id="firstname" aria-describedby="firstnameHelp" placeholder="Votre prénom"
                        value="<?php echo $err_data['firstname']; ?>">
                    <span class="invalid-feedback"><?php echo $err_data['firstname_err']; ?></span>
                </div>
                <div class="form-group col-md-6">
                    <label for="lastname">Nom
                    </label>
                    <input type="text" name="lastname"
                        class="form-control <?php echo (!empty($err_data['lastname_err'])) ? 'is-invalid' : ''; ?>"
                        id="lastname" aria-describedby="lastnameHelp" placeholder="Votre nom"
                        value="<?php echo $err_data['lastname'] ?>">
                    <span class="invalid-feedback"><?php echo $err_data['lastname_err']; ?></span>
                </div>
            </div>
            <div class="form-row">
                <!-- Telephone Fixe -->
                <div class="col-md-6">
                    <label for="telephone">Téléphone fixe
                    </label>
                    <div class="input-group mb-1">
                        <div class="input-group-prepend">
                            <select class="form-control" name="country_code_fix" id="country_code">
                                <?php foreach ($countryCodes as $key => $value): ?>
                                    <option value="<?php echo $value; ?>" <?php echo ($value == $err_data['country_code_fix']) ? 'selected' : '';
                                       ?>>
                                        <?php echo $key . $value; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <input type="text" name="telephone_fix" class="form-control <?php echo (!empty($err_data['telephone_fix_err'])) ? 'is-invalid' : '';
                        ?>" id="telephone" placeholder="Téléphone" value="<?php echo $err_data['telephone_fix']; ?>">
                        <span class="invalid-feedback"><?php echo $err_data['telephone_fix_err']; ?></span>
                    </div>
                </div>
                <!-- Telephone Portable-->
                <div class="col-md-6">
                    <label for="telephone">Téléphone Portable
                    </label>
                    <div class="input-group mb-1">
                        <div class="input-group-prepend">
                            <select class="form-control" name="country_code" id="country_code">
                                <?php foreach ($countryCodes as $key => $value): ?>
                                    <option value="<?php echo $value; ?>" <?php echo ($value == $err_data['country_code']) ? 'selected' : '';
                                       ?>>
                                        <?php echo $key . $value; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <input type="text" name="telephone"
                            class="form-control <?php echo (!empty($err_data['telephone_err'])) ? 'is-invalid' : ''; ?>"
                            id="telephone" placeholder="Téléphone" value="<?php echo $err_data['telephone']; ?>">
                        <span class="invalid-feedback"><?php echo $err_data['telephone_err']; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section professional_info">
        <div class="section_header" data-toggle="collapse" data-target="#professionalInfoCollapse"
            style="cursor: pointer;">
            <h4>Informations professionnelles</h4>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="section_body" id="professionalInfoCollapse">

            <!-- Address -->
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="address">Adresse
                    </label>
                    <input type="text" name="address"
                        class="form-control main_address <?php echo (!empty($err_data['address_err'])) ? 'is-invalid' : ''; ?>"
                        id="address" placeholder="Votre adresse" value="<?php echo $err_data['address'] ?>">
                    <span class="invalid-feedback"><?php echo $err_data['address_err']; ?></span>
                </div>
                <div class="form-group col-md-6">
                    <label for="address">Complément d'adresse</label>
                    <input type="text" name="address_comp" class="form-control main_address" id="address_comp"
                        placeholder="Votre adresse complément">
                </div>
            </div>
            <div class="form-row">
                <!-- Postal Code -->
                <div class="form-group col-md-4">
                    <label for="postalcode">Code postal</label>
                    <input type="text" name="postalcode"
                        class="form-control main_address <?php echo (!empty($err_data['postalcode_err'])) ? 'is-invalid' : ''; ?>"
                        id="postalcode" placeholder="Votre code postal" value="<?php echo $err_data['postalcode'] ?>">
                    <span class="invalid-feedback"><?php echo $err_data['postalcode_err']; ?></span>
                </div>
                <!-- City -->
                <div class="form-group col-md-4">
                    <label for="city">Ville</label>
                    <input type="text" name="city"
                        class="form-control  main_address <?php echo (!empty($err_data['city_err'])) ? 'is-invalid' : ''; ?>"
                        id="city" placeholder="Ville" value="<?php echo $err_data['city'] ?>">
                    <span class="invalid-feedback"><?php echo $err_data['city_err']; ?></span>
                </div>
                <!-- Country -->
                <div class="form-group col-md-4">
                    <label for="country">Pays</label>
                    <input type="text" name="country"
                        class="form-control main_address <?php echo (!empty($err_data['country_err'])) ? 'is-invalid' : ''; ?>"
                        id="city" placeholder="Pays"
                        value="<?php echo ($err_data['country']) ? $err_data['country'] : 'France' ?>">
                    <span class="invalid-feedback"><?php echo $err_data['country_err']; ?></span>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="raison">Organisme associé
                    </label>
                    <input type="text" name="raison"
                        class="form-control <?php echo (!empty($err_data['raison_err'])) ? 'is-invalid' : ''; ?>"
                        id="raison" placeholder="Raison sociale" value="<?php echo ($err_data['raison']);
                        ?>">
                    <span class="invalid-feedback"><?php echo $err_data['raison_err']; ?></span>
                </div>
                <div class="form-group col-md-4">
                    <label for="siret">SIRET</label>
                    <input type="text" name="siret"
                        class="form-control <?php echo (!empty($err_data['siret_err'])) ? 'is-invalid' : ''; ?>"
                        id="siret" aria-describedby="siretHelp" placeholder="Numéro de SIRET"
                        value="<?php echo $err_data['siret'] ?>">
                    <span class="invalid-feedback"><?php echo $err_data['siret_err']; ?></span>
                </div>
                <div class="form-group col-md-4">
                    <label for="ape">Autre employeur</label>
                    <input type="text" name="autre_employeur"
                        class="form-control <?php echo (!empty($err_data['autre_employeur_err'])) ? 'is-invalid' : ''; ?>"
                        id="autre_employeur" aria-describedby="autre_employeurHelp" placeholder="Ex: INPI"
                        value="<?php echo $err_data['autre_employeur'] ?>">
                    <span class="invalid-feedback"><?php echo $err_data['autre_employeur_err']; ?></span>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="tva">Statut</label>
                    <select name="statut"
                        class="form-control <?php echo (!empty($err_data['statut_err'])) ? 'is-invalid' : ''; ?>"
                        id="statut">
                        <option value="0">Sélectionner...</option>
                        <?php foreach ($statut as $value): ?>
                            <option value="<?php echo $value; ?>"
                                <?php echo ($value == $err_data['statut']) ? 'selected' : ''; ?>>
                                <?php echo $value; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="invalid-feedback"><?php echo $err_data['statut_err']; ?></span>
                </div>
                <div class="form-group col-md-6">
                    <label for="recommande_par">Recommandé par</label>
                    <input type="text" name="recommande_par"
                        class="form-control <?php echo (!empty($err_data['recommande_par_err'])) ? 'is-invalid' : ''; ?>"
                        id="recommande_par" aria-describedby="recommande_parHelp"
                        placeholder="Prénom et NOM de la personne" value="<?php echo $err_data['recommande_par'] ?>">
                    <span class="invalid-feedback"><?php echo $err_data['recommande_par_err']; ?></span>
                </div>
            </div>
            <div class="form-row">
                <!-- Function -->
                <div class="form-group col-md-6">
                    <label for="function">Fonction
                    </label>
                    <select class="form-control <?php echo (!empty($err_data['function_err'])) ? 'is-invalid' :
                        ''; ?>" name="function" id="function">
                        <option value="0" selected>Sélectionner...</option>
                        <?php foreach ($functions as $value): ?>
                            <option value="<?php echo $value; ?>"
                                <?php echo ($value == $err_data['function']) ? 'selected' : ''; ?>>
                                <?php echo $value; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="invalid-feedback"><?php echo $err_data['function_err']; ?></span>
                </div>
                <div class="form-group col-md-6">
                    <label for="experience_en_PI">Nombre d'années d'expérience PI</label>
                    <input type="text" name="experience_en_PI"
                        class="form-control <?php echo (!empty($err_data['experience_en_PI_err'])) ? 'is-invalid' : ''; ?>"
                        id="experience_en_PI" aria-describedby="experience_en_PIHelp" placeholder="Ex: 1"
                        value="<?php echo $err_data['experience_en_PI'] ?>">
                    <span class="invalid-feedback"><?php echo $err_data['experience_en_PI_err']; ?></span>
                </div>
            </div>
            <div class="form-group">
                <label for="">
                    Niveau d'études, diplômes, certifications
                </label>
                <textarea type="text" name="niveau_etudes"
                    class="form-control <?php echo (!empty($err_data['niveau_etudes_err'])) ? 'is-invalid' : ''; ?>"
                    id="niveau_etudes" aria-describedby="niveau_etudesHelp"
                    placeholder="Ex : Master en droit international..."><?php echo $err_data['niveau_etudes'] ?></textarea>
                <span class="invalid-feedback"><?php echo $err_data['niveau_etudes_err']; ?></span>
            </div>
            <div class="form-group">
                <label for="experience_formation">
                    Expérience en animation de formation
                </label>
                <textarea type="text" name="experience_formation"
                    class="form-control <?php echo (!empty($err_data['experience_formation_err'])) ? 'is-invalid' : ''; ?>"
                    id="experience_formation" aria-describedby="experience_formationHelp"
                    placeholder="Ex : 3 ans en tant que formateur pour le compte de l’INPI..."><?php echo $err_data['experience_formation'] ?></textarea>
                <span class="invalid-feedback"><?php echo $err_data['experience_formation_err']; ?></span>
            </div>

            <div class="form-group">
                <label for="commentaire">Commentaire</label>
                <textarea type="text" name="commentaire"
                    class="form-control <?php echo (!empty($err_data['commentaire_err'])) ? 'is-invalid' : ''; ?>"
                    id="commentaire" aria-describedby="commentaireHelp"
                    placeholder="Commentaire"><?php echo $err_data['commentaire'] ?></textarea>
                <span class="invalid-feedback"><?php echo $err_data['commentaire_err']; ?></span>
            </div>

        </div>
    </section>

    <section class="section domain_intervention">

        <div class="section_header" data-toggle="collapse" data-target="#domainInterventionCollapse"
            style="cursor: pointer;">
            <h4>Domaines d'intervention</h4>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="section_body" id="domainInterventionCollapse">
            <div class="form-row">
                <div class="form-check col-md-6">
                    <label class="form-check-label container__input" for="prediagnostic_PI">
                        Pré diagnostic PI
                        <input class="form-check-input" type="checkbox" id="prediagnostic_PI" value="Oui"
                            name="prediagnostic_PI"
                            <?php echo ($err_data['prediagnostic_PI'] == 'Oui') ? 'checked' : ''; ?>>
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="form-check col-md-6">
                    <label class="form-check label container__input" for="parrain">
                        Parrain / Marraine
                        <input class="form-check input" type="checkbox" value="Oui" id="parrain" name="parrain"
                            <?php echo ($err_data['parrain'] == 'Oui') ? 'checked' : ''; ?>>
                        <span class="checkmark"></span>
                    </label>
                </div>
            </div>
            <div class="form-row">
                <div class="form-check col-md-6">
                    <label class="form-check-label container__input" for="pass_pi">
                        PASS PI
                        <input class="form-check-input" type="checkbox" value="Oui" id="pass_pi" name="pass_pi"
                            <?php echo ($err_data['pass_pi'] == 'Oui') ? 'checked' : ''; ?>>
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="form-check col-md-6">
                    <label class="form-check-label container__input" for="facile_collaboration">
                        Facilitation collaborative Alliance PI
                        <input class="form-check-input" type="checkbox" value="Oui" id="facile_collaboration"
                            name="facile_collaboration"
                            <?php echo ($err_data['facile_collaboration'] == 'Oui') ? 'checked' : ''; ?>>
                        <span class="checkmark"></span>
                    </label>
                </div>
            </div>

            <div class="form-row">
                <div class="form-check col-md-6">
                    <label class="form-check-label container__input" for="coaching">
                        Coaching
                        <input class="form-check-input" type="checkbox" value="Oui" id="coaching" name="coaching"
                            <?php echo ($err_data['coaching'] == 'Oui') ? 'checked' : ''; ?>>
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="form-check col-md-6">
                    <label class="form-check-label container__input" for="formation_academie">
                        Formation Académie
                        <input class="form-check-input" type="checkbox" value="Oui" id="formation_academie"
                            name="formation_academie"
                            <?php echo ($err_data['formation_academie'] == 'Oui') ? 'checked' : ''; ?>>
                        <span class="checkmark"></span>
                    </label>
                </div>
            </div>

            <div class="form-row">
                <div class="date_container col-md-6">
                    <div class="date_container_header">
                        <p>A reçu une formation pré diagnostic</p>
                    </div>
                    <div class="date_container_body row justify-content-start align-items-center">
                        <label class="container__input">
                            <input class="form-check-input enableDateCheckbox" type="checkbox" <?php
                            echo ($err_data['date_prediagnostic']) ? 'checked' : '';
                            ?> />
                            <span class="checkmark"></span>
                        </label>
                        <input type="date" class="form-control dateInput" placeholder="mm/dd/yyyy"
                            name="date_prediagnostic" disabled value="<?php echo $err_data['date_prediagnostic'] ?>" />
                    </div>
                </div>
                <div class="date_container col-md-6">
                    <div class="date_container_header">
                        <p>A reçu une formation par l'INPI</p>
                    </div>
                    <div class="date_container_body row justify-content-start align-items-center">
                        <label class="container__input">
                            <input class="form-check-input enableDateCheckbox" type="checkbox" <?php
                            echo ($err_data['date_formation_inpi']) ? 'checked' : '';
                            ?> />
                            <span class="checkmark"></span>
                        </label>
                        <input type="date" class="form-control dateInput" placeholder="mm/dd/yyyy"
                            name="date_formation_inpi" disabled
                            value="<?php echo $err_data['date_formation_inpi'] ?>" />
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="date_container col-md-6">
                    <div class="date_container_header">
                        <p>A reçu une formation sur les outils de classe virtuelle</p>
                    </div>
                    <div class="date_container_body row justify-content-start align-items-center">
                        <label class="container__input">
                            <input class="form-check-input enableDateCheckbox" type="checkbox" <?php
                            echo ($err_data['date_formation_outils']) ? 'checked' : '';
                            ?> />
                            <span class="checkmark"></span>
                        </label>
                        <input type="date" class="form-control dateInput" placeholder="mm/dd/yyyy"
                            name="date_formation_outils" disabled
                            value="<?php echo $err_data['date_formation_outils'] ?>" />
                    </div>
                </div>
                <div class="date_container col-md-6">
                    <div class="date_container_header">
                        <p>A participé en tant qu'observateur à une formation</p>
                    </div>
                    <div class="date_container_body row justify-content-start align-items-center">
                        <label class="container__input">
                            <input class="form-check-input enableDateCheckbox" type="checkbox" <?php
                            echo ($err_data['date_formation_observateur']) ? 'checked' : '';
                            ?> />
                            <span class="checkmark"></span>
                        </label>
                        <input type="date" class="form-control dateInput" placeholder="mm/dd/yyyy"
                            name="date_formation_observateur" disabled
                            value="<?php echo $err_data['date_formation_observateur'] ?>" />
                    </div>
                </div>
            </div>

            <div class="form-row align-items-end">
                <div class="date_container col-md-6">
                    <div class="date_container_header">
                        <p>A bénéficié d'un audit pédagogique</p>
                    </div>
                    <div class="date_container_body row justify-content-start align-items-center">
                        <label class="container__input">
                            <input class="form-check-input enableDateCheckbox" id="audit_pedagogic" type="checkbox" <?php
                            echo ($err_data['date_audit_pedagogique']) ? 'checked' : '';
                            ?> />
                            <span class="checkmark"></span>
                        </label>
                        <input type="date" class="form-control dateInput" placeholder="mm/dd/yyyy"
                            name="date_audit_pedagogique" disabled
                            value="<?php echo $err_data['date_audit_pedagogique'] ?>" />
                    </div>
                </div>
                <div class="form-group col-md-6 mb-0">
                    <label for="session_concern">Sessions concernées</label>
                    <input type="text" name="session_concern"
                        class="form-control <?php echo (!empty($err_data['session_concern_err'])) ? 'is-invalid' : ''; ?>"
                        id="session_concern" aria-describedby="session_concernHelp"
                        placeholder="Ex : Intitulé de la formation" value="<?php echo $err_data['session_concern'] ?>"
                        disabled>
                    <span class="invalid-feedback"><?php echo $err_data['session_concern_err']; ?></span>
                </div>
            </div>
        </div>
    </section>

    <section class="section domain_excellence">
        <div class="section_header" data-toggle="collapse" data-target="#domainExcellenceCollapse"
            style="cursor: pointer;">
            <h4>Domaines d'excellence</h4>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="section_body" id="domainExcellenceCollapse">
            <div class="tag-container">
                <span class="invalid-feedback"> <?php echo $err_data['domain_excellence_err']; ?> </span>
                <input type="hidden" name="domain_excellence" id="selectedDomains"
                    value="<?php echo $err_data['domain_excellence']; ?>">
                <?php
                foreach ($domain_excellence as $category => $tags) {
                    echo "<div class='category mt-3' data-category='" . htmlspecialchars($category) . "' data-group='domainExcellence'>$category</div>";
                    echo "<div class='tags mt-2' data-category='" . htmlspecialchars($category) . "'>";
                    foreach ($tags as $tag) {
                        $safeTag = htmlspecialchars($tag);
                        echo "<div class='tag' data-category='" . htmlspecialchars($category) . "' data-group='domainExcellence'>$safeTag</div>";
                    }
                    echo "</div>";
                }
                ?>

            </div>
        </div>
    </section>

    <section class="section zone_intervention">
        <div class="section_header" data-toggle="collapse" data-target="#zoneInterventionCollapse"
            style="cursor: pointer;">
            <h4>Zones d'intervention</h4>
            <i class="fas fa-chevron-down"></i>
        </div>
        <div class="section_body" id="zoneInterventionCollapse">
            <div class="zone-container">
                <?php foreach ($region as $category => $tags) {
                    echo "<div class='category mt-3' data-category='" . htmlspecialchars($category) . "' data-group='zones'>$category</div>";
                    echo "<div class='tags mt-2' data-category='" . htmlspecialchars($category) . "'>";
                    foreach ($tags as $tag) {
                        $safeTag = htmlspecialchars($tag);
                        echo "<div class='tag' data-category='" . htmlspecialchars($category) . "' data-group='zones'>$safeTag</div>";
                    }
                    echo "</div>";
                }
                ?>
                <input type="hidden" name="zone_intervention" id="selectedZones" value="
                <?php echo $err_data['zone_intervention']; ?>
">
            </div>
        </div>
    </section>

    <?php
    // Only display the zone_gestion section if the user is a Gestionnaire INPI or a basefournisseur
    // Or admin
    if ($USER->profile['roleinpi'] == "Gestionnaire INPI") {
        ?>
        <section class="section zone_gestion">
            <div class="section_header" data-toggle="collapse" data-target="#zoneGestionCollapse" style="cursor: pointer;">
                <h4>Zone de gestion</h4>
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="section_body" id="zoneGestionCollapse">
                <div class="zone-container">
                    <div class="form-row align-items-end">
                        <div class="form-group col-md-6 mb-0">
                            <label for="statut_dossier">Statut de dossier</label>
                            <select name="statut_dossier" class="form-control <?php echo (!empty($err_data['statut_dossier_err'])) ? 'is-invalid' :
                                ''; ?>" id="statut_dossier">
                                <option value="0" selected>Sélectionner...</option>
                                <?php foreach ($statut_dossier as $value): ?>
                                    <option value="<?php echo $value; ?>"
                                        <?php echo ($value == $err_data['statut_dossier']) ? 'selected' : ''; ?>>
                                        <?php echo $value; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $err_data['statut_dossier_err']; ?></span>
                        </div>
                        <div class="date_container col-md-6">
                            <div class="date_container_header">
                                <p>Date de validité des documents renouvellables</p>
                            </div>
                            <div class="date_container_body row justify-content-start align-items-center">
                                <label class="container__input">
                                    <input class="form-check-input enableDateCheckbox" type="checkbox" />
                                    <span class="checkmark"></span>
                                </label>
                                <input type="date" class="form-control dateInput" placeholder="mm/dd/yyyy"
                                    name="date_validite_document" disabled />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php
    }
    ?>

    <div class="alert alert-info mt-3">
        <p><b>Note :</b> La création du compte peut prendre jusqu'à 3 min. A la fin du process, un courriel sera envoyé
            au fournisseur pour lui transmettre son mot de passe.</p>
    </div>
    <button type="submit" class="btn btn-primary mb-1" name="submit_provider">Valider les informations</button>
</form>