import { HttpError } from 'wasp/server'

export const getListing = async ({ id }, context) => {
 if (!context.user) { throw new HttpError(401) }
 const listing = await context.entities.Listing.findUnique({
 where: { id },
 include: { bookings: true }
 });
 if (!listing) { throw new HttpError(400, 'Listing with id ' + id + ' does not exist') }
 return listing;
}

export const getUserListings = async (args, context) => {
 if (!context.user) {
 throw new HttpError(401);
 }

 const user = await context.entities.User.findUnique({
 where: { id: context.user.id },
 include: { listings: true }
 });

 if (!user) {
 throw new HttpError(400, 'User does not exist');
 }

 return user.listings;
}