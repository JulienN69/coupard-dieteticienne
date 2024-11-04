import React, { useState } from "react";
import { FaPlus } from "react-icons/fa";

export default function StepsForm() {
	const [steps, setSteps] = useState([{ id: 1, content: "" }]);

	// Fonction pour ajouter une nouvelle étape
	const addStep = (e) => {
		e.preventDefault();
		setSteps([...steps, { id: steps.length + 1, content: "" }]);
	};

	// Fonction pour supprimer la dernière étape
	const removeStep = (e) => {
		e.preventDefault();
		if (steps.length > 1) {
			setSteps(steps.slice(0, -1));
		}
	};

	// Fonction pour gérer le changement de contenu d'une étape
	const handleStepChange = (index, event) => {
		const newSteps = [...steps];
		newSteps[index].content = event.target.value;
		setSteps(newSteps);
	};

	return (
		<div>
			{steps.map((step, index) => (
				<div key={step.id} className="form-group">
					<h2 className="form__label">Étape {step.id}</h2>
					<textarea
						placeholder="Étape pour la réalisation de la recette"
						value={step.content}
						onChange={(e) => handleStepChange(index, e)}
					/>
				</div>
			))}
			<div className="btn-container">
				<div>
					<button className="form__btn" onClick={addStep}>
						<FaPlus />
					</button>
					<span>Ajouter une étape</span>
				</div>
				<button className="form__btn" onClick={removeStep}>
					Supprimer la dernière étape
				</button>
				<button className="form__btn" type="submit">
					Valider
				</button>
			</div>
		</div>
	);
}
