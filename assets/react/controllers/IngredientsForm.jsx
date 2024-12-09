import React, { useEffect, useState } from "react";
import { useLoadIngredientData } from "../hooks/useFetch";
import { FaChevronDown, FaChevronUp, FaPlus } from "react-icons/fa";
import AddButton from "./AddButton";

const className = (...args) =>
	args
		.flatMap((arg) =>
			typeof arg === "object"
				? Object.keys(arg).filter((key) => arg[key])
				: arg
		)
		.join(" ");

export default function IngredientsForm({ IngredientsData = [] }) {
	console.log("ingredientsData:", IngredientsData);

	const { items, load, loading } = useLoadIngredientData("/api/categories");
	const [categories, setCategories] = useState([]);
	const [selectedIngredients, setSelectedIngredients] = useState([]);
	const [parsedIngredientsData, setParsedIngredientsData] = useState(() => {
		const parsedData = Array.isArray(IngredientsData)
			? IngredientsData
			: JSON.parse(IngredientsData);

		return parsedData.map((ingredient) => ({
			quantity: ingredient.quantity,
			name: ingredient.ingredientName,
		}));
	});

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

	useEffect(() => {
		// Ajoute les ingrédients déjà dans la recette à `selectedIngredients`
		const initialSelection = parsedIngredientsData.map((item) => item.name);
		setSelectedIngredients(initialSelection);
	}, [parsedIngredientsData]);

	const handleIngredientSelect = (ingredientName) => {
		const isSelected = selectedIngredients.includes(ingredientName);

		if (isSelected) {
			// Supprimer l'ingrédient des ingrédients sélectionnés et des données existantes
			setSelectedIngredients((prevSelected) =>
				prevSelected.filter((item) => item !== ingredientName)
			);
			setParsedIngredientsData((prevData) =>
				prevData.filter((item) => item.name !== ingredientName)
			);
		} else {
			// Ajouter l'ingrédient aux ingrédients sélectionnés
			setSelectedIngredients((prevSelected) => [
				...prevSelected,
				ingredientName,
			]);
			setParsedIngredientsData((prevData) => [
				...prevData,
				{ name: ingredientName, quantity: "" }, // Ajouter avec une quantité vide
			]);
		}
	};

	const handleQuantityChange = (ingredientName, newQuantity) => {
		setParsedIngredientsData((prevData) =>
			prevData.map((ingredient) =>
				ingredient.name === ingredientName
					? { ...ingredient, quantity: newQuantity }
					: ingredient
			)
		);
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
						selectedIngredients={selectedIngredients}
						onIngredientSelect={handleIngredientSelect}
					/>
				))}
			</div>
			<div className="ingredients-selected-list">
				{parsedIngredientsData.map((ingredient) => (
					<IngredientInput
						key={ingredient.name}
						ingredientName={ingredient.name}
						quantity={ingredient.quantity}
						onRemove={() => handleIngredientSelect(ingredient.name)}
						onQuantityChange={handleQuantityChange}
					/>
				))}
			</div>
			<AddButton label="Créer un ingrédient" />
		</div>
	);
}

function IngredientsField({
	categorie,
	totalIngredients,
	selectedIngredients,
	onIngredientSelect,
}) {
	const [isOpen, setIsOpen] = useState(false);

	const onClickIngredient = (ingredientName) => {
		onIngredientSelect(ingredientName);
	};

	const toggleOpen = (e) => {
		e.preventDefault();
		setIsOpen(!isOpen);
	};

	return (
		<div className={className("card-categorie", isOpen && "isOpen")}>
			<div className="card-categorie__head">
				<div className="card-categorie__head-title">
					<h2>{categorie}</h2>
					<span>{totalIngredients.length} ingrédients</span>
				</div>
				<button
					className="card-categorie__head-btn"
					onClick={toggleOpen}
				>
					{isOpen ? <FaChevronUp /> : <FaChevronDown />}
				</button>
			</div>
			{isOpen && (
				<div className="card-categorie__content">
					{totalIngredients.map((ingredient) => (
						<div
							className={className(
								"card-categorie__content-ingredient",
								{
									selected: selectedIngredients.includes(
										ingredient.name
									),
								}
							)}
							key={ingredient.id}
							onClick={() => onClickIngredient(ingredient.name)}
						>
							<div>{ingredient.name}</div>
						</div>
					))}
				</div>
			)}
		</div>
	);
}

function IngredientInput({
	ingredientName,
	quantity,
	onRemove,
	onQuantityChange,
}) {
	const [currentQuantity, setCurrentQuantity] = useState(quantity || "");
	const [errors, setErrors] = useState("");

	const validateQuantity = (value) => {
		const regex = /^(?:[1-9][0-9]{0,3}|10000)\s*(?:[a-zA-Z]*)?$/;
		if (!value) return "La quantité est obligatoire (max : 10000).";
		if (!regex.test(value))
			return "Vous devez indiquer un chiffre, puis éventuellement une unité de mesure";
		return "";
	};

	const handleChange = (e) => {
		const value = e.target.value;
		setCurrentQuantity(value);
		setErrors(validateQuantity(value));
		if (!validateQuantity(value)) {
			onQuantityChange(ingredientName, value);
		}
	};

	return (
		<div className={`ingredient-selected ${errors ? "error" : ""}`}>
			<div
				className="ingredient-selected__div"
				// onClick={onRemove} // Supprime l'ingrédient
			>
				{ingredientName}
			</div>
			<input
				className="ingredient-selected__input"
				type="text"
				placeholder="Quantité"
				value={currentQuantity}
				onChange={handleChange}
				onClick={(e) => e.stopPropagation()} // Empêche la suppression accidentelle
			/>
			{errors && <div className="error">{errors}</div>}
		</div>
	);
}
