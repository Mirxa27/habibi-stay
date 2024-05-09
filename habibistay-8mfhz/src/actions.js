import { HttpError } from 'wasp/server'

export const createListing = async (args, context) => {
  if (!context.user) { throw new HttpError(401) };

  return context.entities.Listing.create({
    data: {
      address: args.address,
      price: args.price,
      availableFrom: args.availableFrom,
      availableUntil: args.availableUntil,
      maxGuests: args.maxGuests,
      userId: context.user.id
    }
  });
}

export const makeBooking = async (args, context) => {
  if (!context.user) { throw new HttpError(401) };

  const { dateFrom, dateUntil, guests } = args;

  const listing = await context.entities.Listing.findFirst({
    where: { availableFrom: { lte: dateFrom }, availableUntil: { gte: dateUntil }, id: args.listingId }
  });

  if (!listing || guests > listing.maxGuests) { throw new HttpError(400) };

  const booking = await context.entities.Booking.create({
    data: {
      dateFrom,
      dateUntil,
      guests,
      userId: context.user.id,
      listingId: args.listingId
    }
  });

  return booking;
}
