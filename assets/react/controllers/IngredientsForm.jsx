import React, { useEffect, useState } from "react";
import { useLoadIngredientData } from "../hooks/useFetch";
import { FaChevronDown, FaChevronUp, FaPlus } from "react-icons/fa";
import AddButton from "./AddButton";

const className = (...args) => {
	return args
		.flatMap((arg) => {
			if (typeof arg === "string") return arg;
			if (typeof arg === "object")
				return Object.keys(arg).filter((key) => arg[key]);
			return [];
		})
		.join(" ");
};

export default function IngredientsForm() {
	const { items, load, loading } = useLoadIngredientData("/api/categories");
	const [categories, setCategories] = useState([]);
	const [selectedIngredients, setSelectedIngredients] = useState([]); // État pour les ingrédients sélectionnés

	useEffect(() => {
		load();
	}, [load]);

	useEffect(() => {
		if (items && items.length > 0) {
			setCategories(items);
		} else if (!loading) {
			console.error("Erreur lors du chargement des catégories");
		}
	}, [items, loading]);

	// Fonction pour gérer la sélection des ingrédients
	const handleIngredientSelect = (ingredient) => {
		setSelectedIngredients((prevSelected) => {
			if (prevSelected.includes(ingredient)) {
				return prevSelected.filter((item) => item !== ingredient);
			} else {
				return [...prevSelected, ingredient];
			}
		});
	};

	return (
		<div>
			<h3 className="form__label">Ingrédients</h3>
			<div className="section-ingredient">
				{categories.map((categorie) => (
					<IngredientsField
						key={categorie.id}
						categorie={categorie.name}
						totalIngredients={Object.values(categorie.ingredients)}
						visibleIngredients={
							Object.values(categorie.ingredients).length
						}
						onIngredientSelect={handleIngredientSelect} // Passe la fonction au composant enfant
					/>
				))}
			</div>
			{selectedIngredients.map((ingredient) => (
				<IngredientInput key={ingredient} ingredient={ingredient} />
			))}
			<AddButton label="Créer un ingrédient" />
		</div>
	);
}

function IngredientsField({
	categorie,
	visibleIngredients,
	totalIngredients,
	onIngredientSelect,
}) {
	const [isOpen, setIsOpen] = useState(false);
	const [ingredientSelected, setIngredientSelected] = useState([]);

	const onClickIngredient = (e, ingredientText) => {
		const isSelected = ingredientSelected.includes(ingredientText);
		const updatedSelection = isSelected
			? ingredientSelected.filter((item) => item !== ingredientText)
			: [...ingredientSelected, ingredientText];
		setIngredientSelected(updatedSelection);
		onIngredientSelect(ingredientText); // Appelle la fonction parent avec l'ingrédient sélectionné
	};

	const onClick = (e) => {
		e.preventDefault();
		setIsOpen(!isOpen);
	};

	return (
		<div className={className("card-categorie", isOpen && "isOpen")}>
			<div className="card-categorie__head">
				<div className="card-categorie__head-title">
					<h2>{categorie}</h2>
					<span>{visibleIngredients} ingrédients</span>
				</div>
				<button className="card-categorie__head-btn" onClick={onClick}>
					{isOpen ? <FaChevronUp /> : <FaChevronDown />}
				</button>
			</div>

			<div className="card-categorie__content">
				{totalIngredients.map((ingredient) => (
					<div
						className={className(
							"card-categorie__content-ingredient",
							{
								selected: ingredientSelected.includes(
									ingredient.name
								),
							}
						)}
						key={ingredient.id}
						onClick={(e) => onClickIngredient(e, ingredient.name)}
					>
						<div>{ingredient.name}</div>
					</div>
				))}
			</div>
		</div>
	);
}

function IngredientInput({ ingredient }) {
	const [quantity, setQuantity] = useState("");

	// Fonction pour synchroniser avec le champ masqué
	useEffect(() => {
		const hiddenInput = document.querySelector(
			`input[name="ingredients[${ingredient}]"]`
		);
		if (hiddenInput) {
			hiddenInput.value = quantity;
		}
	}, [quantity, ingredient]);

	return (
		<div className="ingredient-selected">
			<div className="ingredient-selected__div">{ingredient}</div>
			<input
				className="ingredient-selected__input"
				type="text"
				placeholder="Quantité"
				value={quantity}
				onChange={(e) => setQuantity(e.target.value)}
			/>
			{/* Champ masqué pour la synchronisation avec le formulaire */}
			<input
				type="hidden"
				name={`ingredients[${ingredient}]`}
				value={quantity}
			/>
		</div>
	);
}
