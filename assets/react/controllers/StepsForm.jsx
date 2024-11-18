import React, { useState } from "react";
import { FaPlus, FaMinus } from "react-icons/fa";
import AddButton from "./AddButton";

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
					<h2 className="form-group__title">Étape {step.id}</h2>
					<textarea
						placeholder="description de l'étape"
						className="form__textarea"
						value={step.content}
						onChange={(e) => handleStepChange(index, e)}
					/>
				</div>
			))}
			<div className="Step-buttons">
				<AddButton label="Ajouter une étape" onClick={addStep} />
				<AddButton
					label="Supprimer la dernière étape"
					onClick={removeStep}
					icon={<FaMinus />}
				/>
			</div>
		</div>
	);
}
